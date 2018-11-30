UpgradeMODX Extra for MODX Revolution
=======================================


**Author:** Bob Ray [Bob's Guides](https://bobsguides.com)

**Contributors:** Dmytro Lukianenko (dmi3y), Susan Sottwell, Bumkaka, Sharapov, Inreti, Zaigham Rana, frischnetz, and AgelxNash.

**Documentation:** [UpgradeMODX Docs](https://bobsguides.com/upgrade-modx-package.html)

**Bugs and requests:** [UpgradeMODX Issues](https://github.com/BobRay/UpgradeMODX/issues)
  
**Questions about using UpgradeMODX** [MODX Forums](https://forums.modx.com)

Always back up your files and database tables before performing an upgrade.

This package was inspired by the work of a number of people and I have borrowed some of their code. Dmytro Lukianenko (dmi3yy) is the original author of the MODX install script. Susan Sottwell, Inreti, Zaigham Rana, frischnetz, and AgelxNash, also contributed and I'd like to thank all of them for laying the groundwork.

This Package (UpgradeMODX) is designed to upgrade MODX from the dashboard of the MODX Manager.

The package installs a dashboard widget that reports whether an upgrade is available. When an upgrade is available, an Upgrade MODX button will appear that will launch the install form. The form gives you the option to select a version of MODX to upgrade to. Once you've selected a version, UpgradeMODX will download the files, put them in the proper locations, and launch setup (be patient, this part takes some time).
 
 See the properties tab of the UpgradeMODXWidget snippet for the options and their descriptions and be sure to read the documentation at the link above.
 
 It's always a good idea to back up your site before clicking on the Install button. If downloading the files succeeds and setup fails, your site may be broken.
 
 