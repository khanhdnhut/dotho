<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<link rel="shortcut icon" type="image/x-icon" href="<?php
if (isset($this->metadata) && isset($this->metadata->icon)) {
    echo $this->metadata->icon;
} else {
    echo PUBLIC_IMG . "sondong/favicon.ico";
}

?>">
<link rel="canonical" href="<?php
if (isset($this->metadata) && isset($this->metadata->canonical)) {
    echo $this->metadata->canonical;
} else {
    echo PUBLIC_URL;
}

?>">
<meta property="og:url" content="<?php
if (isset($this->metadata) && isset($this->metadata->url)) {
    echo $this->metadata->url;
} else {
    echo PUBLIC_URL;
}

?>">

<meta property="og:image" content="<?php
if (isset($this->metadata) && isset($this->metadata->image)) {
    echo $this->metadata->image;
} else {
    echo PUBLIC_IMG . "sondong/favicon.ico";
}

?>">	
<meta property="og:description" content="<?php
if (isset($this->metadata) && isset($this->metadata->description)) {
    echo $this->metadata->description;
} else {
    echo DEFAULT_DESCRIPTION_WEBSITE;
}

?>">

<meta name="description" content="<?php
if (isset($this->metadata) && isset($this->metadata->description)) {
    echo $this->metadata->description;
} else {
    echo DEFAULT_DESCRIPTION_WEBSITE;
}

?>">

<meta property="og:type" content="<?php
if (isset($this->metadata) && isset($this->metadata->type)) {
    echo $this->metadata->type;
} else {
    echo "website";
}

?>">

<title><?php
    if (isset($this->metadata) && isset($this->metadata->title)) {
        echo $this->metadata->title;
    } else {
        echo DEFAULT_TITLE_WEBSITE;
    }

    ?></title>
<meta property="og:title" content="<?php
if (isset($this->metadata) && isset($this->metadata->title)) {
    echo $this->metadata->title;
} else {
    echo DEFAULT_TITLE_WEBSITE;
}

?>">
<meta name="geo.region" content="<?php
if (isset($this->metadata) && isset($this->metadata->region)) {
    echo $this->metadata->region;
} else {
    echo DEFAULT_GEO_REGION;
}

?>">
<meta property="og:email" content="<?php
if (isset($this->metadata) && isset($this->metadata->email)) {
    echo $this->metadata->email;
} else {
    echo DEFAULT_EMAIL;
}

?>"> 
<meta property="og:phone_number" content="<?php
if (isset($this->metadata) && isset($this->metadata->phone)) {
    echo $this->metadata->phone;
} else {
    echo DEFAULT_PHONE_VALUE;
}

?>"> 

<meta name="geo.position" content="<?php
if (isset($this->metadata) && isset($this->metadata->position)) {
    echo $this->metadata->position;
} else {
    echo DEFAULT_GEO_POSITION;
}

?>">
<meta name="ICBM" content="<?php
if (isset($this->metadata) && isset($this->metadata->position)) {
    echo $this->metadata->position;
} else {
    echo DEFAULT_GEO_POSITION;
}

?>">
<meta name="geo.placename" content="<?php
if (isset($this->metadata) && isset($this->metadata->placename)) {
    echo $this->metadata->placename;
} else {
    echo DEFAULT_ADDRESS;
}

?>"> 
<meta name="DC.title" content="<?php
if (isset($this->metadata) && isset($this->metadata->dc_title)) {
    echo $this->metadata->dc_title;
} else {
    echo DEFAULT_MAP_TITLE;
}

?>"> 

<meta name="viewport" content="<?php
if (isset($this->metadata) && isset($this->metadata->viewport)) {
    echo $this->metadata->viewport;
} else {
    echo "width=device-width, initial-scale=1.0";
}

?>">

<meta name="robots" content="index,follow"/>
<meta name="keywords" content="<?php
if (isset($this->metadata) && isset($this->metadata->keywords)) {
    echo $this->metadata->keywords;
} else {
    echo DEFAULT_KEYWORD;
}

?>"/>
<meta property="og:site_name" content="<?php
if (isset($this->metadata) && isset($this->metadata->site_name)) {
    echo $this->metadata->site_name;
} else {
    echo DEFAULT_SITE_NAME;
}

?>"/>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<meta rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php
if (isset($this->metadata) && isset($this->metadata->apple_touch_icon_precomposed_72)) {
    echo $this->metadata->apple_touch_icon_precomposed_72;
} else {
    echo PUBLIC_IMG . HEADER_APPLE_TOUCH_ICON_PRECOMPOSED_72_DEFAULT;
}

?>"/>

<meta rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php
if (isset($this->metadata) && isset($this->metadata->apple_touch_icon_precomposed_144)) {
    echo $this->metadata->apple_touch_icon_precomposed_144;
} else {
    echo PUBLIC_IMG . HEADER_APPLE_TOUCH_ICON_PRECOMPOSED_114_DEFAULT;
}

?>"/>

<meta rel="apple-touch-icon-precomposed" sizes="57x57" href="<?php
if (isset($this->metadata) && isset($this->metadata->apple_touch_icon_precomposed_57)) {
    echo $this->metadata->apple_touch_icon_precomposed_57;
} else {
    echo PUBLIC_IMG . HEADER_APPLE_TOUCH_ICON_PRECOMPOSED_57_DEFAULT;
}

?>"/>

<meta rel="apple-touch-icon-precomposed" sizes="1x1" href="<?php
if (isset($this->metadata) && isset($this->metadata->apple_touch_icon_precomposed_1)) {
    echo $this->metadata->apple_touch_icon_precomposed_1;
} else {
    echo PUBLIC_IMG . HEADER_APPLE_TOUCH_ICON_PRECOMPOSED_1_DEFAULT;
}

?>"/>

<link rel="nokia-touch-icon" href="<?php
if (isset($this->metadata) && isset($this->metadata->nokia_touch_icon)) {
    echo $this->metadata->nokia_touch_icon;
} else {
    echo PUBLIC_IMG . HEADER_NOKIA_TOUCH_ICON_DEFAULT;
}

?>"/>

<link rel="shortcut icon" type="image/x-icon" href="<?php
if (isset($this->metadata) && isset($this->metadata->nokia_touch_icon)) {
    echo $this->metadata->nokia_touch_icon;
} else {
    echo PUBLIC_IMG . HEADER_NOKIA_TOUCH_ICON_DEFAULT;
}

?>"/>

<!--Search Engine Robots Meta Tags-->

<meta name="revisit-after" content="10 days">
<meta name="googlebot" content="noodp">
<meta name="msnbot" content="noodp">
<meta name="slurp" content="noodp, noydir"> <!--yahoo-->
<meta name="teoma" content="noodp"> <!--ask-->
<meta name="robots" content="noodp, noydir">
<meta name="robots" content="index, follow">
<meta name="robots" content="noindex, follow">
<meta name="robots" content="noindex, nofollow">
<meta name="robots" content="index, follow, noodp, noydir, noarchive"> 

<!--Authoring Meta Tags-->

<meta name="author" content="<?php
if (isset($this->metadata) && isset($this->metadata->site_name)) {
    echo $this->metadata->site_name;
} else {
    echo DEFAULT_SITE_NAME;
}

?>">
<meta name="publisher" content="<?php
if (isset($this->metadata) && isset($this->metadata->publisher)) {
    echo $this->metadata->publisher;
} else {
    echo PUBLIC_URL;
}

?>">
<meta name="copyright" content="<?php
if (isset($this->metadata) && isset($this->metadata->copyright)) {
    echo $this->metadata->copyright;
} else {
    echo PUBLIC_URL;
}

?>">
<meta name="host" content="<?php
if (isset($this->metadata) && isset($this->metadata->host)) {
    echo $this->metadata->host;
} else {
    echo PUBLIC_URL;
}

?>">
<meta name="generator" content="<?php
if (isset($this->metadata) && isset($this->metadata->site_name)) {
    echo $this->metadata->site_name;
} else {
    echo DEFAULT_SITE_NAME;
}

?>"> 