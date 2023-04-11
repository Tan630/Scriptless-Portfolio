

# Building

The complete product can be built from two sources: `source/layout.css.php` and `source/index.html.php`. 
Running `make all` compiles them to `styles/layout.css` and `index.html` respectively.

The content of each tab is stored at `source/resources/tabs`. A file `taborder.json` may exist in this directory. It should contain key-value pairs where the keys can be ordered and each value is a file name.

If the values are defined, the tabs are arranged in this order; otherwise they are arranged in lexicographic order.

`source/resources/parts` contains the "parts" used to build the tabs. Each file must have a dummy root node, since libxml can only safely import documents with a single root node.

In this version, all code made by myself is released under the MIT license (see `LICENSE`) 

# Notes to Self

At this point, a number of methods that work with PHP DOM objects remain unorganized. A good number of them still use dummy `DOMDocument` objects to host temporary elements, which result in memory leak if the memory monitor is to be believed. Still, the amount of memory wasted is negligible for a project of this size.

Migit organize and release the custom-made libraries (both the ODOM interface for generating CSSDOM and the collection of PHPDOM helpers) in the future.


# Licenses

This project makes use of the following resources:

CSS normalizer github.com/necolas/normalize.css under the MIT license (see `licenses/NORMALIZE-LICENCE`)

Openiconic https://github.com/iconic/open-iconic under the MIT license and in part the SIL Open Font License (see `licenses/OI-FONT-LICENCE` and `licenses/OI-ICON-LICENCE`)