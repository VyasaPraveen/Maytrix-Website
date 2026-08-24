<?php
/**
 * Country → representative time zone map.
 * Used to render the Country dropdown and auto-fill the Time zone field.
 * Focused on Maytrix's markets (India, the Gulf, the UK, Europe) plus common
 * others. Time zone is an IANA identifier with a familiar abbreviation.
 */

return [
    'India'                => 'Asia/Kolkata (IST)',
    'United Arab Emirates' => 'Asia/Dubai (GST)',
    'Saudi Arabia'         => 'Asia/Riyadh (AST)',
    'Qatar'                => 'Asia/Qatar (AST)',
    'Kuwait'               => 'Asia/Kuwait (AST)',
    'Bahrain'              => 'Asia/Bahrain (AST)',
    'Oman'                 => 'Asia/Muscat (GST)',
    'United Kingdom'       => 'Europe/London (GMT/BST)',
    'Ireland'              => 'Europe/Dublin (GMT/IST)',
    'Germany'              => 'Europe/Berlin (CET)',
    'France'               => 'Europe/Paris (CET)',
    'Netherlands'          => 'Europe/Amsterdam (CET)',
    'Belgium'              => 'Europe/Brussels (CET)',
    'Switzerland'          => 'Europe/Zurich (CET)',
    'Spain'                => 'Europe/Madrid (CET)',
    'Italy'                => 'Europe/Rome (CET)',
    'Portugal'             => 'Europe/Lisbon (WET)',
    'Sweden'               => 'Europe/Stockholm (CET)',
    'Norway'               => 'Europe/Oslo (CET)',
    'Denmark'              => 'Europe/Copenhagen (CET)',
    'Finland'              => 'Europe/Helsinki (EET)',
    'Poland'               => 'Europe/Warsaw (CET)',
    'Austria'              => 'Europe/Vienna (CET)',
    'Greece'               => 'Europe/Athens (EET)',
    'Turkey'               => 'Europe/Istanbul (TRT)',
    'United States'        => 'America/New_York (ET)',
    'Canada'               => 'America/Toronto (ET)',
    'Singapore'            => 'Asia/Singapore (SGT)',
    'Malaysia'             => 'Asia/Kuala_Lumpur (MYT)',
    'Hong Kong'            => 'Asia/Hong_Kong (HKT)',
    'Australia'            => 'Australia/Sydney (AEST/AEDT)',
    'New Zealand'          => 'Pacific/Auckland (NZST/NZDT)',
    'South Africa'         => 'Africa/Johannesburg (SAST)',
    'Kenya'                => 'Africa/Nairobi (EAT)',
    'Nigeria'              => 'Africa/Lagos (WAT)',
    'Egypt'                => 'Africa/Cairo (EET)',
    'Pakistan'            => 'Asia/Karachi (PKT)',
    'Bangladesh'          => 'Asia/Dhaka (BST)',
    'Sri Lanka'           => 'Asia/Colombo (IST)',
    'Nepal'               => 'Asia/Kathmandu (NPT)',
];
