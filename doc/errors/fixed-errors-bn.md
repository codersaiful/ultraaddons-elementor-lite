## UltraAddons Elementor Lite - ফিক্স করা এররসমূহ

এই ডকুমেন্টে `ultraaddons-elementor-lite` প্লাগিনে পাওয়া নির্দিষ্ট Notice/Deprecated/Warning ইস্যুগুলোর ফিক্স সংক্ষেপে দেয়া হলো।

---

### 1) Notice: textdomain খুব early stage-এ trigger হচ্ছিল

**এরর মেসেজ (সারাংশ):**
`Translation loading for the ultraaddons-elementor-lite domain was triggered too early`

**কারণ:**
প্লাগিনের loader (`loader.php`) `plugins_loaded` ধাপে ইনক্লুড হচ্ছিল, কিন্তু loader-এর ভিতরে widget array থেকে translation function (`__()`) ব্যবহার হচ্ছিল। WordPress 6.7+ এ এটা early trigger হিসেবে Notice দেয়।

**ফিক্স:**
- `init.php`-তে `init()` মেথডের ভিতর থেকে সরাসরি `loader.php` include না করে `init` action-এ (`priority 20`) defer করা হয়েছে।
- নতুন `load_loader()` মেথড যোগ করা হয়েছে।
- এতে textdomain load হওয়ার পরে translation call হয়, তাই Notice আর আসে না।

---

### 2) Deprecated: Dynamic property in `Placeholder_Extension`

**এরর মেসেজ (সারাংশ):**
`Creation of dynamic property UltraAddons\Extensions\Placeholder_Extension::$name is deprecated`

**কারণ:**
ক্লাসে `$name` property declare করা ছিল না, কিন্তু constructor-এ `$this->name` assign করা হচ্ছিল।

**ফিক্স:**
- `inc/extensions/placeholder-extension.php` ফাইলে `protected $name = '';` property declare করা হয়েছে।

---

### 3) Deprecated: Dynamic property in `Loader`

**এরর মেসেজ (সারাংশ):**
`Creation of dynamic property UltraAddons\Loader::$widgetsArray is deprecated`

**কারণ:**
`loader.php`-তে `$this->widgetsArray` ব্যবহার করা হচ্ছিল, কিন্তু class property হিসেবে declare ছিল না।

**ফিক্স:**
- `loader.php`-তে `public $widgetsArray = array();` declare করা হয়েছে।

---

### 4) Warning: term item থেকে `name` property read করতে গিয়ে warning

**এরর মেসেজ (সারাংশ):**
`Warning: Attempt to read property "name" on array in inc/core/custom-fonts-handle.php on line 472`

**কারণ:**
`get_terms()` থেকে loop-এ আসা item সবসময় object হবে ধরে `$term->name` ব্যবহার করা হয়েছিল। কিছু ক্ষেত্রে array আসায় warning দিচ্ছিল।

**ফিক্স:**
- `inc/core/custom-fonts-handle.php`-এ `is_wp_error()` check যোগ করা হয়েছে।
- `foreach`-এ term object ও array—দুটো ফরম্যাটই safe ভাবে handle করা হয়েছে।
- valid name পাওয়া গেলে তবেই font list-এ add করা হচ্ছে।

---

### 5) Custom Fonts মেনুতে "Invalid taxonomy" দেখাচ্ছিল

**এরর মেসেজ (সারাংশ):**
`Invalid taxonomy.` — Custom Fonts পেজ খুলতে গেলে 500 error + Invalid taxonomy।

**কারণ (২টি):**

**কারণ ১ — Timing Issue:**
আমাদের Fix #1 এ `loader.php` কে `init` priority 20 তে defer করা হয়েছিল। ফলে `Custom_Fonts_Taxonomy::__construct()` এর ভিতরে `add_action('init', [$this, 'register_taxonomy'])` call হচ্ছিল এমন সময়ে যখন `init` action ইতিমধ্যে fire হয়ে গেছে। WordPress আর সেই callback execute করে না — taxonomy কখনো register হয়নি।

**কারণ ২ — capabilities array format ভুল:**
```php
// ভুল (indexed array):
'capabilities' => array( ULTRA_ADDONS_CAPABILITY ),

// সঠিক (associative array):
'capabilities' => array(
    'manage_terms' => ULTRA_ADDONS_CAPABILITY,
    'edit_terms'   => ULTRA_ADDONS_CAPABILITY,
    'delete_terms' => ULTRA_ADDONS_CAPABILITY,
    'assign_terms' => ULTRA_ADDONS_CAPABILITY,
),
```
WordPress-এর `register_taxonomy()` নির্দিষ্ট key দিয়ে capability map করে। Indexed array দিলে কোনো key match হয় না, তাই ব্যবহারকারীর access ঠিকমতো কাজ করত না।

**ফিক্স (`inc/wp/custom-fonts-taxonomy.php`):**
- `__construct()` এ `did_action('init')` চেক যোগ করা হয়েছে। যদি `init` ইতিমধ্যে fired হয়ে গিয়ে থাকে, তাহলে `register_taxonomy()` সরাসরি call করা হয়। অন্যথায় `add_action('init', ...)` ব্যবহার করা হয়।
- `capabilities` array সঠিক associative format এ ঠিক করা হয়েছে।

---

### 6) Custom Fonts "Upload Font" বাটন কাজ করছিল না

**এরর মেসেজ (Browser Console):**
`TypeError: Cannot read properties of undefined (reading 'frames')` — `admin.js:59`

**কারণ:**
`admin.js` এ `wp.media(...)` call করা হচ্ছে Font upload এর জন্য, কিন্তু WordPress Media Uploader scripts (`wp-media`) কোথাও load করা হয়নি। `wp_enqueue_media()` না থাকায় `wp.media` object undefined ছিল।

**ফিক্স (`admin/admin-handle.php`):**
- `get_enqueue()` মেথডে `get_current_screen()` দিয়ে চেক করা হয় যে আমরা `ultraaddons-custom-fonts` taxonomy পেজে আছি কিনা।
- সেই পেজে থাকলে `wp_enqueue_media()` call করা হয়।
- এতে `wp.media` object available হয়, media uploader modal সঠিকভাবে খোলে।

---

## যেসব ফাইল পরিবর্তন করা হয়েছে

| ক্রমিক | ফাইল | কারণ |
|--------|------|-------|
| 1 | `init.php` | textdomain early trigger fix; `load_loader()` method যোগ |
| 2 | `loader.php` | `$widgetsArray` property declare; `bootstrap_widgets()` method; নতুন Elementor API support |
| 3 | `inc/extensions/placeholder-extension.php` | `$name` property declare |
| 4 | `inc/core/custom-fonts-handle.php` | `get_terms()` result safe handling |
| 5 | `inc/wp/custom-fonts-taxonomy.php` | timing fix + capabilities array fix |
| 6 | `admin/admin-handle.php` | `wp_enqueue_media()` Custom Fonts পেজে যোগ |

