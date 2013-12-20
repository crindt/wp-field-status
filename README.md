# What?

Silly little plugin to store field status in config settings and expose them via shortcodes.


# Installation

Install in wordpress plugins dir.


# Configuration/Updating Field Status

In the dashboard, go to Settings => Cardiff Soccer Field Status Menu

Click on Open/Closed as appropriate.  Add an optional comment (currently just used as a 
title/popup in the open/closed span).

# Shortcode

You can insert current field status content using the shortcode:

```
   [field-status [fmt="(brian|ul)"] [field=("berkich|ada|csp")]]
```

## Formatting

The `fmt` parameter controls formatting:

* `brian` will format the field status in a manner consistent with
  current formatting on the site so that: `[field-status fmt="brian"]`
  results in something like the following:


```html
<div class= "f-wrap">
  <dl>Cardiff Elementary (Berkich)</dl>
  <dt>South: <span title="Closing Dec 21st" class="open">Open</span></dt>
  <dt>North: <span title="Opening January 15th" class="closed">Closed</span></dt>
  <dl>Ada Harris Elementary</dl>
  <dt>East: <span title="Opening Feb 1st" class="closed">Closed</span></dt>
  <dt>West: <span title="Opening Feb 1st" class="closed">Closed</span></dt>
  <dl>Cardiff Sports Park (Lake)</dl>
  <dt>Upper: <span title="Unavailable to Cardiff Soccer until late spring" class="closed">Closed</span></dt>
  <dt>Lower: <span title="Unavailable to Cardiff Soccer until late spring" class="closed">Closed</span></dt>
</div>
```

* `ul` will format the field status using dl/ul nesting, so that:
  `[field-status fmt="ul"]` results in something like the following:

```HTML
<dl class= "f-wrap">
  <dt>Cardiff Elementary (Berkich)</dt>
  <dd>
    <ul>
      <li>South: <span title="Closing Dec 21st" class="open">Open</span></li>
      <li>North: <span title="Opening January 15th" class="closed">Closed</span></li>
    </ul>
  </dd>
  <dt>Ada Harris Elementary</dt>
  <dd>
    <ul>
      <li>East: <span title="Opening Feb 1st" class="closed">Closed</span></li>
      <li>West: <span title="Opening Feb 1st" class="closed">Closed</span></li>
    </ul>
  </dd>
  <dt>Cardiff Sports Park (Lake)</dt>
  <dd>
    <ul>
      <li>Upper: <span title="Unavailable to Cardiff Soccer until late spring" class="closed">Closed</span></li>
      <li>Lower: <span title="Unavailable to Cardiff Soccer until late spring" class="closed">Closed</span></li>
    </ul>
  </dd>
</dl>
```

## Displaying only a single field

The `field` parameter can be used to limit the output to the sides of a
specific field.  E.g., to display the current status of Berkich (with
default formatting).  Fields recognized are:

* berkich
* ada
* csp

The shortcode

```
[field-status field="berkich"]
```

would output the following using the default formatting:

```
<ul>
  <li>South: <span title="Closing Dec 21st" class="open">Open</span></li>
  <li>North: <span title="Opening January 15th" class="closed">Closed</span></li>
 </ul>
```

or the following using `brian` formatting:

```
<dt>South: <span title="Closing Dec 21st" class="open">Open</span></dt>
<dt>North: <span title="Opening January 15th" class="closed">Closed</span></dt>
```

