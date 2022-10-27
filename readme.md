![UltraAddons Elementor Lit](https://raw.githubusercontent.com/codersaiful/ultraaddons-elementor-lite/master/assets/images/svg/full-color-logo.svg)



# How to Contribute?
As **[UltraAddons](https://wordpress.org/plugins/ultraaddons-elementor-lite/)**(UltraAddons – Elementor Addons) Plugin is a Elementor Plugin of WordPress. So Need first WordPress then Elementor. 
You can make any new Widget which is not available in UltraAddons or you can updated and fixed any existing Widget, Extension or any other Functionality.

Check following steps:
- Install WordPress to your localhost. It can be [WAMP](https://www.wampserver.com/en/), [XAMP](https://www.apachefriends.org/) etc. Although I prefer **XAMP**. You can use any one.
- Install [Elementor](https://wordpress.org/plugins/elementor/) Plugin to your WordPress Site.
- Activate Elementor Plugin and import some product.
- Clone `woo-product-table` repository to your Plugins directory. Repository [Clone Tutorial](https://docs.github.com/en/repositories/creating-and-managing-repositories/cloning-a-repository).
  - Go to `plugins` like `C:\wamp64\www\{wordpress-site}\wp-content\plugins` 
  - open comand tool. such as Git Bash. A screenshot:<br>
  ![image](https://user-images.githubusercontent.com/6463919/197454363-660a92ee-d9f1-45f3-8869-21546fd30084.png)
  - write `git clone https://github.com/codersaiful/woo-product-table.git` And press ENTER.
  - I recommend you to pull latest branch but you can pull master branch also AND *Obviously create new branch from this branch with your name/username etc*.
  - After fix, push. We will check and merge.
  - RECOMMENDED: Everytime pull latest version.
- Now open your Localhost WordPress site's code via any code Editor. like [VS Code](https://code.visualstudio.com/), [Netbeans](https://netbeans.apache.org/) etc.
- I strongly recommend to open your main WordPress CMS folder via CODE EDITOR. Your site's probable directory is: `C:\wamp64\www\{wordpress-site}`.
- **UltraAddons** plugin directory is: `C:\wamp64\www\{wordpress-site}\wp-content\plugins\ultraaddons-elementor-lite`
- Go to Dashboard -> Plugins and Activate **UltraAddons*
- Check all functionality and Findout issue. Or Making a new **Features** for Product Table Plugins.
- Creating a table: Dashboard -> Product Table -> Add New -> put name, set some columns and **Publish** Post. Then copy that shortcode and Paste to your desired page.
- Create a new issue on this ripository and add Label `hacktoberfest`, `good first issue`, `hacktobarfest2022` and `codeastrology`



