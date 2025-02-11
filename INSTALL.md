# 🧪 Google Tag Manager Installation Guide

1. Navigate to your Magento project root folder:

```shell
cd /path/to/magento
```

2. Run the following commands:

```shell
composer require acid-unit/module-google-tag-manager
bin/magento module:enable AcidUnit_GoogleTagManager
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:flush
```

## 🔎 Verification

After installation, ensure the module is properly enabled by running:

```shell
bin/magento module:status | grep GoogleTagManager
```

If installed correctly, you should see:

```shell
AcidUnit_GoogleTagManager
```
