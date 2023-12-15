<?php

$this->head->meta->add('viewport', 'width=device-width, initial-scale=1, user-scalable=no');
$this->head->link->add('https://fonts.googleapis.com', 'preconnect');
$this->head->link->add('https://fonts.gstatic.com', 'preconnect', ['crossorigin' => '']);
$this->head->css->addFile('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;500&family=Roboto:ital,wght@0,400;0,700;1,400&display=swap');

$this->head->css->addFile('/css/fa.min.css');
//$head->css->addFile('/css/global.css');
//$head->css->addFile('/css/template.primary.css');
//$head->css->addFile('/css/form.css');
//$head->css->addFile('/css/type.css');

//$head->javascript->addFile('/js/template.primary.js');
//$head->javascript->addFile('/js/form.js');

echo '<!doctype html>';
echo '<html lang="en" prefix="og: https://ogp.me/ns#">';
echo '<head>';
//echo '<base href="https://adept.travel/">';
echo '{{head}}';
echo '</head>';

echo '<body>';
echo '<header>';
echo '<div>';

echo '<div>';
echo '<a href="tel:2242088609" class="call"><img src="/img/icon/fa/solid/phone.svg" alt="Call us"  width="25" height="25"></a>';
echo '<a href="/" class="logo"><img src="/img/logo.svg" alt="The Adept Traveler" width="36" height="30"></a>';
echo '<a class="show" ><img src="/img/icon/fa/solid/bars.svg" alt="Show menu" width="22" height="25"></a>';
echo '</div>';

echo '{{menu:main}}';
/*
echo '<nav class="main">';
echo '<ul>';
echo '<li><a href="/" aria-current="page">Home</a></li>';
echo '<li><a href="/types">Travel Types</a></li>';
echo '<li><a href="/destinations">Destinations</a></li>';
echo '<li><a href="/suppliers">Suppliers</a></li>';
echo '<li><a href="/travel-explained">Travel Explained</a></li>';
echo '<li><a href="/today-in-travel">Today in Travel</a></li>';
echo '<li><a href="/blogs">Blogs</a></li>';
echo '<li><a href="/podcasts">Podcasts</a></li>';
echo '</ul>';
echo '</nav>';
*/

echo '</div>';
echo '</header>';


echo '<div id="content">';
echo '<main>{{component}}</main>';
echo '</div>';


echo '<div id="copyright" itemprop="copyrightNotice">';
echo '&copy; 2021 - ' . date('Y') . ' ';
echo '<span itemprop="publisher" itemscope itemtype="https://schema.org/Organization">The Adept Traveler, Inc.</span>';
echo ' All Rights Reserved.';
echo '</div>';


echo '<footer>';

echo '<nav class="policy">';
echo '<ul>';
echo '<li><a href="/accessibility-statement">Accessibility Statement</a></li>';
echo '<li><a href="/cookie-policy">Cookie Policy</a></li>';
echo '<li><a href="/privacy-policy">Privacy Policy</a></li>';
echo '<li><a href="/terms-conditions">Terms &amp; Conditions</a></li>';
echo '</ul>';
echo '</nav>';

echo '<div class="address">';
echo '<h3>Address &amp; Phone</h3>';
echo '<p>165 East Chicago Street<br>Elgin, Illinois 60120</p>';
echo '<p><a href="https://www.google.com/maps/dir//the+adept+traveler/data=!4m6!4m5!1m1!4e2!1m2!1m1!1s0x880f05244bfd54b3:0x333249652f54e6b9?sa=X&amp;ved=2ahUKEwjC9pToqND2AhUVK80KHb-eBcIQ9Rd6BAgVEAU" target="_blank" rel="noopener">Get Directions</a></p>';
echo '<h4>Phone</h4>';
echo '<p><a href="tel:2242088609">(224) 208-8609</a></p>';
echo '</div>';

echo '<div class="destinations">';
echo '<h3>Popular Destinations</h3>';
echo '<ul>';
echo '<li><a href="/destinations/aruba">Aruba</a></li>';
echo '<li><a href="/destinations/bahamas">Bahamas</a></li>';
echo '<li><a href="/destinations/portugal">Portugal</a></li>';
echo '<li><a href="/destinations/guadeloupe">Guadeloupe</a></li>';
echo '<li><a href="/destinations/st-lucia">St. Lucia</a></li>';
echo '<li><a href="/destinations/europe">Europe</a></li>';
echo '<li><a href="/destinations/barbados">Barbados</a></li>';
echo '</ul>';
echo '</div>';

/*
echo '<nav class="social">';
echo '<ul>';
echo '<li><a href="https://youtube.com/@AdeptTraveler"><img src="/img/icon/fa/brands/youtube.svg" alt="YouTube" loading="lazy"><span class="image-title">YouTube</span></a></li>';
echo '<li><a href="https://www.facebook.com/adepttraveler"><img src="/img/icon/fa/brands/facebook.svg" alt="Facebook" loading="lazy"><span class="image-title">Facebook</span></a></li>';
echo '<li><a href="https://twitter.com/AdeptTraveler"><img src="/img/icon/fa/brands/twitter.svg" alt="Twitter" loading="lazy"><span class="image-title">Twitter</span></a></li>';
echo '<li><a href="https://www.linkedin.com/company/adepttraveler"><img src="/img/icon/fa/brands/linkedin.svg" alt="LinkedIn" loading="lazy"><span class="image-title">LinkedIn</span></a></li>';
echo '<li><a href="https://www.instagram.com/adepttraveler/"><img src="/img/icon/fa/brands/instagram.svg" alt="Instagram" loading="lazy"><span class="image-title">Instagram</span></a></li>';
echo '<li><a href="https://www.tiktok.com/@theadepttraveler?lang=en"><img src="/img/icon/fa/brands/tiktok.svg" alt="TikTok" loading="lazy"><span class="image-title">TikTok</span></a></li>';
echo '</ul>';
echo '</nav>';
*/
echo '{{menu:social}}';

echo '<div class="types">';
echo '<h3>Popular Types of Travel</h3>';
echo '<ul>';
echo '<li><a href="/types/european-cruise">European Cruise</a></li>';
echo '<li><a href="/types/honeymoon">Honeymoon</a></li>';
echo '<li><a href="/types/family-cruise">Family Cruise</a></li>';
echo '<li><a href="/types/costal-cruise">Costal Cruise</a></li>';
echo '<li><a href="/types/solar-eclipse-cruise">Solar Eclipse Cruise</a></li>';
echo '<li><a href="/types/group">Group Travel</a></li>';
echo '<li><a href="/types/wine">Wine Lovers</a></li>';
echo '</ul>';
echo '</div>';

echo '<div class="company">';
echo '<h3>Company</h3>';
echo '<nav>';
echo '<ul>';
echo '<li><a href="/company">About Us</a></li>';
echo '<li><a href="/company/news">Company News</a></li>';
echo '<li><a href="/?Itemid=198">Awards &amp; Honors</a></li>';
echo '<li><a href="/company/our-team">Our Team</a></li>';
echo '<li><a href="/company/serving">Proudly Service</a></li>';
echo '</ul>';
echo '</nav>';
echo '</div>';

echo '<div class="assoc">';
echo '<h3>Proud Member of</h3>';
echo '<a class="asta" href="https://www.asta.org/membership/directory-search-details?memId=900312505"><img src="/img/association/asta.svg" alt="American Society of Travel Advisors" width="300" height="150" loading="lazy"></a>';
echo '<a class="clia" href="https://cruising.org" target="_blank" alt="Cruise Line Indusitry Association" width="300" height="150"><img src="/img/association/clia.svg" alt="" width="300" height="150" loading="lazy"></a>';
echo '<a class="bbb" href="https://www.bbb.org/us/il/elgin/profile/travel-agency/the-adept-traveler-inc-0654-1000038783/#sealclick"><img src="/img/association/bbb.svg" target="_blank" alt="Better Business Bureau" width="300" height="150" loading="lazy"></a>';
echo '</div>';
echo '</footer>';

echo '</body>';
echo '</html>';
