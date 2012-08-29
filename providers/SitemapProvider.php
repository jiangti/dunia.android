<?php
class SitemapProvider extends Aw_Tool_Framework_ProviderAbstract {
	
	public function create() {
		$sitemap = '<?xml version="1.0" encoding="UTF-8"?>
                    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $sitemap .= '<url>
            <loc>http://www.dunia.com.au/</loc>
            <lastmod>' . date("Y-m-d") . '</lastmod>
            </url>';

        $sitemap .= '<url>
            <loc>http://www.dunia.com.au/pub/email</loc>
            <lastmod>' . date("Y-m-d") . '</lastmod>
            </url>';

        $sitemap .= '<url>
            <loc>http://www.dunia.com.au/pub</loc>
            <lastmod>' . date("Y-m-d") . '</lastmod>
            </url>';

        $sitemap .= '<url>
            <loc>http://www.dunia.com.au/blog/about-us</loc>
            <lastmod>' . date("Y-m-d") . '</lastmod>
            </url>';

        $sitemap .= '<url>
            <loc>http://www.dunia.com.au/user/login</loc>
            <lastmod>' . date("Y-m-d") . '</lastmod>
            </url>';

        $sitemap .= '<url>
            <loc>http://www.dunia.com.au/index/help</loc>
            <lastmod>' . date("Y-m-d") . '</lastmod>
            </url>';

        $pubModel = new Model_DbTable_Pub();
        $pubs = $pubModel->fetchAll('checkinsCount > 20');

        foreach ($pubs as $pub) {
            $sitemap .= '<url>
                <loc>http://www.dunia.com.au' . $pub->getPermalink() . '</loc>
                <lastmod>' . date("Y-m-d") . '</lastmod>
                </url>';
        }

        $sitemap .= '</urlset>';

        file_put_contents(APPLICATION_ROOT . '/public/sitemap.xml', $sitemap);
	}
	

}
