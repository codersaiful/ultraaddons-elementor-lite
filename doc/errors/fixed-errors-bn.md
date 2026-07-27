## UltraAddons Elementor Lite - ফিক্স করা এররসমূহ

এই ডকুমেন্টে `ultraaddons-elementor-lite` প্লাগিনে পাওয়া নির্দিষ্ট Notice/Deprecated ইস্যুগুলোর ফিক্স সংক্ষেপে দেয়া হলো।

### 1) Notice: textdomain খুব early stage-এ trigger হচ্ছিল

**এরর মেসেজ (সারাংশ):**
`Translation loading for the ultraaddons-elementor-lite domain was triggered too early`

**কারণ:**
প্লাগিনের loader (`loader.php`) `plugins_loaded` ধাপে ইনক্লুড হচ্ছিল, কিন্তু loader-এর ভিতরে widget array থেকে translation function (`__()`) ব্যবহার হচ্ছিল। WordPress 6.7+ এ এটা early trigger হিসেবে Notice দেয়।

**ফিক্স:**
- `init.php`-তে `init()` মেথডের ভিতর থেকে সরাসরি `loader.php` include না করে `init` action-এ (`priority 20`) defer করা হয়েছে।
- নতুন `load_loader()` মেথড যোগ করা হয়েছে।
- এতে textdomain load হওয়ার পরে translation call হয়, তাই Notice আর আসে না।

### 2) Deprecated: Dynamic property in `Placeholder_Extension`

**এরর মেসেজ (সারাংশ):**
`Creation of dynamic property UltraAddons\Extensions\Placeholder_Extension::$name is deprecated`

**কারণ:**
ক্লাসে `$name` property declare করা ছিল না, কিন্তু constructor-এ `$this->name` assign করা হচ্ছিল।

**ফিক্স:**
- `inc/extensions/placeholder-extension.php` ফাইলে `protected $name = '';` property declare করা হয়েছে।

### 3) Deprecated: Dynamic property in `Loader`

**এরর মেসেজ (সারাংশ):**
`Creation of dynamic property UltraAddons\Loader::$widgetsArray is deprecated`

**কারণ:**
`loader.php`-তে `$this->widgetsArray` ব্যবহার করা হচ্ছিল, কিন্তু class property হিসেবে declare ছিল না।

**ফিক্স:**
- `loader.php`-তে `public $widgetsArray = array();` declare করা হয়েছে।

### 4) Warning: term item object না হয়ে array আসার কারণে `name` read warning

**এরর মেসেজ (সারাংশ):**
`Warning: Attempt to read property "name" on array in inc/core/custom-fonts-handle.php on line 472`

**কারণ:**
`get_terms()` থেকে loop-এ আসা item সবসময় object হবে ধরে `$term->name` ব্যবহার করা হয়েছিল। কিছু ক্ষেত্রে array আসায় warning দিচ্ছিল।

**ফিক্স:**
- `inc/core/custom-fonts-handle.php`-এ `is_wp_error()` check যোগ করা হয়েছে।
- `foreach`-এ term object/array—দুটো ফরম্যাটই safe ভাবে handle করা হয়েছে।
- valid name পাওয়া গেলে তবেই font list-এ add করা হচ্ছে।

---

## যেসব ফাইল পরিবর্তন করা হয়েছে

1. `init.php`
2. `loader.php`
3. `inc/extensions/placeholder-extension.php`
4. `inc/core/custom-fonts-handle.php`
5. `doc/errors/fixed-errors-bn.md` (এই ডকুমেন্ট)
