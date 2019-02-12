# Mantisbt Plugin HotfixVersionSupport
A plugin for the Mantis BugTracker which allows to assign tickets a HotFix version and provides an overview of HotFix progress and contained tickets (like Roadmap or Changelog for "normal" versions)

It works by using two (user-definable) custom fields to assign tickets a *target hotfix version* and a *fixed in hotfix* marker (like Mantis's original *target version* and *fixed in version*). This means that issues can be assigned to two versions at once, a regular version and a hotfix version. 
This allows to have the issue appear in the changelog of the next regular version where it is fixed and keep track of hotfixes which a deployed before the next regular version and already contain a fix/change for the given issue.

## Installation

As usual, copy the plugin files to a subfolder called "HotfixVersionSupport" of your mantis directory. Enabled the plugin in Mantis's Plugin management page.

You have to create two custom fields of type enumeration, *Target hotfix* and *Fixed in hotfix*. Their value has to be set to ´´´=hotfix´versions´´´ (which allows us to provide teh values by a custom function). The two fields need to be assigned to all project you want to track hotfix versions for.

In order to have the values of these fields populated you have to include the function ´´´custom_function_override_enum_hotfixversions´´´ contained in *custom_functions_inc.php* into you Mantis installation's *config/custom_functions_inc.php* file. If you do not have a custom_functions_inc.php file yet, you may just copy the provided one to your conf folder.

## Configuration

In order to configure the plugin, access its configuration page by clicking on its name on the *manage plugins* page of Mantis's management site.
In the configuration you have to select the two previously created custom fields in *general configuration*.
Add each project you want to have hotfix support for to the project configuration and type the enumeration value for the selectable hotfix versions into the provided text field. This uses the regular custom field enumeration style, i.e. *Item1|Item2|Item3*.
So, to allow users to select the Hotfix versions HF1 and HF2 and the empty entry (=no hotfix association) you would type *|HF1|HF2*.

## Usage

For supported projects the sidebar entry *Hotfixes* will be available which navigates to the hotfix overview page. There all hotfix versions which have been associated with tickets are shown in a Roadmap/Changelog like list. Currently the versions are sorted naturally (by using PHP's ´´´natsort´´´), no dates can be specified. For each hotfix versions, issues which have either *Target hotfix*, *Fixed in hotfix* or both set to the corresponding version are listed. Issues which have a value for *Fixed in hotfix* are considered completed for the hotfix (i.e. striked out), independent of the actual issues status value.
