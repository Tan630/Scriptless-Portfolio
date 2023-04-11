<?
include_once './source/lib/ConcreteRules.php';
include_once './source/lib/Indent.php';

use OCSSDOM\Abstract\CSSMediaRule;
use OCSSDOM\Concrete\ConcreteCSSMediaRule;

use OCSSDOM\Abstract\CSSStyleSheet;
use OCSSDOM\Concrete\ConcreteCSSStyleSheet;
use OCSSDOM\Tools\Indent;

/** ---------- Preamble underneath this line ----------
 * Rules in this document:
 * f-container: contains flex elements, wraps
 * f-auto: flex item, automatic width
 * f-break: flex item, line break
 * f-row: flex row, f-{sz}-row is the dynamic version
 * d-inline: sets display mode to inline, d-{sz}-inline is the dynamic version
 * d-block: sets display mode to block, d-{sz}-block is the dynamic version
 * fi-{sz}-#: flex item that takes # columns, out of 12
 * m-# sets margin to #, p-{sz}-# is a the dynamic version.
 * p-# sets padding to #, p-{sz}-# is a the dynamic version.
 * m{l|r|t|b}-# sets directional margin to #, p-{sz}-{l|r|t|b}-# is a the dynamic version
 * p{l|r|t|b}-# sets directional padding to #, p-{sz}-{l|r|t|b}-# is a the dynamic version
 * fi-#: marks a flex item that takes # out of 12 columns, fi-{sz}-# is the dynamic version
 */

$output = "";
$columns = 24;


$paddingPresets = [1,2,3,4,5,6];
$marginPresets = [1,2,3,4,5,6];

$mediaBreakpoints = [
    "xxl" => "none",
    "xl" => "1400px",
    "lg" => "1200px",
    "md" => "992px",
    "sm" => "768px",
    "xs" => "576px",

  
];


$mediaRules = [];
foreach ($mediaBreakpoints as $key => $val) {
    $newRule = new ConcreteCSSMediaRule();
    
    $newRule->getMedia()->appendMedium("max-width: {$mediaBreakpoints[$key]}");
    $mediaRules[$key] = $newRule;
} unset ($result);

function appendToMediaRule(string $key, string $value) {
    global $mediaRules;
    $mediaRules[$key]-> insertRule($value, 0);
}


/** ---------- Rule declarations start here ----------
 * A few strategies are applied here: 
 *   - The body element collects a number of CSS variables. The values of these variables
 *     are taken from global variables defined in this file.
 *   - The variable $output collects the output (including 'normal' rules
 *     and media rules). 
 *   - Call `appendToMediaRule` to add to a media rule. There is currently no way of removing
 *     from a media rule, as realizing the function would require me to implement a CSSDOM parser
 *     and I the project is already bloated as it is.
 */

/** The following rules set CSS variables for `body`.
 * 
 */


$paddings = 5;
$margins = 5;

$defaultMarginX = "3px";
$defaultMarginY = "3px";
$defaultPaddingX = "3px";
$defaultPaddingY = "3px";

/**
 * Font size
 * 
 */

$output .= <<<EOD
body {
  font-size: 100%;
}
\n
EOD;
 


/** Rules that relate to font size
 * t-lt, t-rg, and t-hv: weight
 * t-sm, t-md, and t-lg: size
 */

$output .= <<<EOD
.t-lt {
  font-weight: lighter;
}
.t-nm {
  font-weight: normal;
}
.t-hv {
  font-weight: bolder;
}
.t-bd {
  font-weight: bold;
}
.t-sm {
  font-size: 85%;
}
.t-md {
  font-size: 100%;
}
.t-lg {
  font-size: 125%;
}
EOD;
foreach ($mediaBreakpoints as $key => $val) {
    appendToMediaRule($key,<<<EOD
    .t-{$key}-lt {
      font-weight: lighter;
    }
    .t-{$key}-nm {
      font-weight: normal;
    }
    .t-{$key}-hv {
      font-weight: bolder;
    }
    .t-{$key}-bd {
      font-weight: bold;
    }
    .t-{$key}-sm {
      font-size: 85%;
    }
    .t-{$key}-md {
      font-size: 100%;
    }
    .t-{$key}-lg {
      font-size: 125%;
    }
    EOD);
}


/** Rules that start with f- relate to the flex layout:
 * f-container: contains flex elements, wraps
 * f-auto: flex item, automatic width
 * f-break: flex item, line break
 * f-row: flex row, f-{sz}-row is the dynamic version
 * d-inline: sets display mode to inline, d-{sz}-inline is the dynamic version
 * d-block: sets display mode to block, d-{sz}-block is the dynamic version
 */
$output .= <<<EOD
.f-container {
  display: flex;
  flex-wrap: wrap;
  flex-direction: column;
  flex: 1 1 auto;
}
.f-auto {flex: 1 1 0;}
.f-break {flex: 0 0 100%;}\n
EOD;

$output .= <<<EOD
.d-inline {display: inline;}
.d-block {display: block;}
.d-flex {display: flex;}
.d-align-center {margin: auto;}
\n

EOD;
foreach ($mediaBreakpoints as $key => $val) {
    appendToMediaRule($key,<<<EOD
    .d-{$key}-inline {display: inline;}
    .d-{$key}-block {display: block;}
    .d-{$key}-flex {display: block;}
    .d-{$key}-align-center {margin: auto;}
    EOD);
}

$output .= <<<EOD
.f-row {
  display: flex;
  flex-wrap: nowrap;
  width: 100%;
  flex-direction: row;
}\n
.f-wrow {
    display: flex;
    flex-wrap: wrap;
    width: 100%;
    flex-direction: row;
  }\n
EOD;
foreach ($mediaBreakpoints as $key => $val) {
    appendToMediaRule($key,
    <<<EOD
    .f-{$key}-row {
      display: flex;
      flex-wrap: nowrap;
      width: 100%;
      flex-direction: row;
    }
    .f-{$key}-wrow {
        display: flex;
        flex-wrap: wrap;
        width: 100%;
        flex-direction: row;
      }
    EOD);
}

/**
 * Rules that define responsive margins and paddings.
 * m-# sets margin to #, p-{sz}-# is a the dynamic version.
 * p-# sets padding to #, p-{sz}-# is a the dynamic version.
 * m{l|r|t|b}-# sets directional margin to #, p-{sz}-{l|r|t|b}-# is a the dynamic version
 * p{l|r|t|b}-# sets directional padding to #, p-{sz}-{l|r|t|b}-# is a the dynamic version
 */

for ($i = 0; $i <= $paddings; $i ++) {
  $remDistance = $i /4;
  $output .= <<<EOD
  .p-{$i} {
    padding-left: {$remDistance}rem;
    padding-right: {$remDistance}rem;
    padding-top: {$remDistance}rem;
    padding-bottom: {$remDistance}rem;
  }
  .py-{$i} {
    padding-top: {$remDistance}rem;
    padding-bottom: {$remDistance}rem;
  }
  .px-{$i} {
    padding-left: {$remDistance}rem;
    padding-right: {$remDistance}rem;
  }
  .pl-{$i} {padding-left: {$remDistance}rem}
  .pr-{$i} {padding-right: {$remDistance}rem}
  .pt-{$i} {padding-top: {$remDistance}rem}
  .pb-{$i} {padding-bottom: {$remDistance}rem}\n
  EOD;
  
  foreach ($mediaBreakpoints as $key => $val) {
    appendToMediaRule($key,
    <<<EOD
    .p-{$key}-{$i} {
      padding-left: {$remDistance}rem;
      padding-right: {$remDistance}rem;
      padding-top: {$remDistance}rem;
      padding-bottom: {$remDistance}rem;
    }
    .py-{$key}-{$i} {
        padding-top: {$remDistance}rem;
        padding-bottom: {$remDistance}rem;
      }
    .px-{$key}-{$i} {
      padding-left: {$remDistance}rem;
      padding-right: {$remDistance}rem;
    }
    .pl-{$key}-{$i} {padding-left: {$remDistance}rem}
    .pr-{$key}-{$i} {padding-right: {$remDistance}rem}
    .pt-{$key}-{$i} {padding-top: {$remDistance}rem}
    .pb-{$key}-{$i} {padding-bottom: {$remDistance}rem}\n
    EOD);
  } unset($remDistance);
} 

for ($i = 0; $i <= $paddings; $i ++) {
    $remDistance = $i /4;
  $output .= <<<EOD
  .m-{$i} {
    margin-left: {$remDistance}rem;
    margin-right: {$remDistance}rem;
    margin-top: {$remDistance}rem;
    margin-bottom: {$remDistance}rem;
  }
  .mx-{$i} {
    margin-left: {$remDistance}rem;
    margin-right: {$remDistance}rem;
  }
  .my-{$i} {
    margin-top: {$remDistance}rem;
    margin-bottom: {$remDistance}rem;
  }
  .ml-{$i} {margin-left: {$remDistance}rem}
  .mr-{$i} {margin-right: {$remDistance}rem}
  .mt-{$i} {margin-top: {$remDistance}rem}
  .mb-{$i} {margin-bottom: {$remDistance}rem}\n
  EOD;
  foreach ($mediaBreakpoints as $key => $val) {
    appendToMediaRule($key,
    <<<EOD
    .m-{$key}-{$i} {
      margin-left: {$remDistance}rem;
      margin-right: {$remDistance}rem;
      margin-top: {$remDistance}rem;
      margin-bottom: {$remDistance}rem;
    }
    .mx-{$key}-{$i} {
      margin-left: {$remDistance}rem;
      margin-right: {$remDistance}rem;
    }
    .my-{$key}-{$i} {
      margin-top: {$remDistance}rem;
      margin-bottom: {$remDistance}rem;
    }
    .ml-{$key}-{$i} {margin-left: {$remDistance}rem;}
    .mr-{$key}-{$i} {margin-right: {$remDistance}rem;}
    .mt-{$key}-{$i} {margin-top: {$remDistance}rem;}
    .mb-{$key}-{$i} {margin-bottom: {$remDistance}rem;}\n
    EOD);
  }
} unset($remDistance);




/** Rules that start with f- relate to the flex layout:
 * f-container: contains flex elements, wraps
 * f-auto: flex item, automatic width
 * f-break: flex item, line break
 * fi-{sz}-#: flex item that takes # columns, out of 12
 * m-# sets margin to #, p-{sz}-# is a the dynamic version.
 * p-# sets padding to #, p-{sz}-# is a the dynamic version.
 * m{l|r|t|b}-# sets directional margin to #, p-{sz}-{l|r|t|b}-# is a the dynamic version
 * p{l|r|t|b}-# sets directional padding to #, p-{sz}-{l|r|t|b}-# is a the dynamic version
 */

/**
 * fi-#: marks a flex item that takes # out of 12 columns, fi-{sz}-# is the dynamic version
 */
for ($i = 1; $i <= $columns; $i ++) {
  $elemWidth = 100 / $columns * $i - 0.1; // `-1` is to correct for 
  $output .= <<<EOD
  .fi-{$i} {
    flex: 0 0 {$elemWidth}%;
    
  }\n
  EOD;
  // width: {$elemWidth}%;

  foreach ($mediaBreakpoints as $key => $val) {
    appendToMediaRule($key,
    <<<EOD
    .fi-{$key}-{$i} {
      flex: 0 0 {$elemWidth}%;
    }\n
    EOD);
    // width: {$elemWidth}%;
  }
} unset($i);

/** Visibility rules that show / hide elements at certain breakpoints.
 * v-h hides, v-v shows, d-none disables.
 * v-{sz}-h, v-{sz}-v, and d-{sz}-none are dynamic.
 */

$output .= <<<EOD
.v-h {visibility: hidden;}
.v-v {visibility: visible;}
.d-none {display: none;}
\n

EOD;
foreach ($mediaBreakpoints as $key => $val) {
    appendToMediaRule($key,<<<EOD
    .v-{$key}-h {visibility: hidden;}
    .v-{$key}-v {visibility: visible;}
    .d-{$key}-none {display: none;}
    EOD);
}

/** These rules set the dimensions of an element.
 * w-# sets the width of an element to #%. h-# does the same for height.
 * w-{sz}-# and h-{sz}-# are dynamic.
 */

foreach ([25, 50, 75, 100] as $sz) {
    $output .= <<<EOD
    .w-{$sz} {width: {$sz}%}
    .h-{$sz} {width: {$sz}%}\n
    
    EOD;
    foreach ($mediaBreakpoints as $key => $val) {
        appendToMediaRule($key,<<<EOD
        .w-{$key}-{$sz} {width: {$sz}%}
        .h-{$key}-{$sz} {width: {$sz}%}
        EOD);
    } unset($val);
} unset($sz);



// This line prints all "normal" output
echo $output;

// This line prints all media rules.
foreach($mediaRules as $rule) {
    echo(Indent::indentString($rule));
}
?>

