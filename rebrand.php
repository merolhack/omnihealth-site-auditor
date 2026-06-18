<?php
$dir = __DIR__;

// 1. Rename files
$file_renames = [
    'omnihealth-site-auditor.php' => 'pressvitals-site-auditor.php',
    'languages/omnihealth-site-auditor.pot' => 'languages/pressvitals-site-auditor.pot',
    'languages/omnihealth-site-auditor-es_ES.po' => 'languages/pressvitals-site-auditor-es_ES.po',
    'languages/omnihealth-site-auditor-es_ES.mo' => 'languages/pressvitals-site-auditor-es_ES.mo',
    'includes/class-ohsa-admin.php' => 'includes/class-pvsa-admin.php',
    'includes/class-ohsa-engine.php' => 'includes/class-pvsa-engine.php',
    'includes/class-ohsa-rest.php' => 'includes/class-pvsa-rest.php',
];

foreach ($file_renames as $old => $new) {
    if (file_exists("$dir/$old")) {
        rename("$dir/$old", "$dir/$new");
        echo "Renamed $old to $new\n";
    }
}

// 2. Global Find and Replace
$replacements = [
    'OmniHealthSiteAuditor' => 'PressVitalsSiteAuditor',
    'OmniHealth: Deep Site Auditor' => 'PressVitals Site Auditor',
    'omnihealth-site-auditor' => 'pressvitals-site-auditor',
    'OmniHealth' => 'PressVitals',
    'omnihealth' => 'pressvitals',
    'OHSA_' => 'PVSA_',
    'ohsa_' => 'pvsa_',
    'class-ohsa' => 'class-pvsa', // Just in case
];

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
foreach ($iterator as $file) {
    if ($file->isDir()) continue;
    $path = $file->getPathname();
    
    // Skip git, vendor, node_modules
    if (strpos($path, '.git') !== false || strpos($path, 'vendor') !== false || strpos($path, 'node_modules') !== false) {
        continue;
    }
    
    // Only process text files
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    if (!in_array($ext, ['php', 'txt', 'md', 'json', 'yml', 'xml', 'po', 'pot', 'sh'])) {
        continue;
    }
    
    // Skip this script itself
    if (basename($path) === 'rebrand.php') {
        continue;
    }
    
    $content = file_get_contents($path);
    $new_content = $content;
    foreach ($replacements as $search => $replace) {
        $new_content = str_replace($search, $replace, $new_content);
    }
    
    // Specific fixes that might be case-sensitive or need overriding:
    // Contributors list: update to merolhack
    if (basename($path) === 'readme.txt' || basename($path) === 'pressvitals-site-auditor.php') {
        $new_content = preg_replace('/Contributors:\s*.+/', 'Contributors: merolhack', $new_content);
        $new_content = preg_replace('/Author:\s*.+/', 'Author: merolhack', $new_content);
    }
    
    if ($content !== $new_content) {
        file_put_contents($path, $new_content);
        echo "Updated $path\n";
    }
}

echo "Done.\n";
