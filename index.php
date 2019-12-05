<?php

require_once __DIR__ . '/lib/matomo.php';

Kirby::plugin('sylvainjule/matomo', array(
	'options' => array(
		'token'      => false,
		'url'        => false,
		'id'         => false,
		'active'     => true,
		'debug'      => false,
        'blacklist'  => ['127.0.0.1', '::1'],
        'trackUsers' => false,
	),
	'snippets' => array(
        'matomo' => __DIR__ . '/lib/snippets/matomo.php'
    ),
	'sections' => array(
        'matomo-main' => array(
        	'props' => array(
        		'chart' => function($chart = true) {
        			return $chart;
        		},
        		'overview' => function($overview = true) {
        			return $overview;
        		},
        		'periods' => function($periods = ['year', 'month', 'week', 'day']) {
        			return $periods;
        		},
        		'widgets' => function($widgets = ['referrerType', 'websites', 'socials', 'devicesType', 'keywords', 'popularPages']) {
        			return $widgets;
        		},
        		'defaults' => function($defaults = ['period' => 'month', 'limit' => 5]) {
        			return $defaults;
        		},
        	),
        	'computed' => array(
        		'lang' => function() {
        			return kirby()->user()->language();
        		}
        	)
        ),
        'matomo-sidebar' => array(
        	'props' => array(
        		'link' => function($link = true) {
        			return $link;
        		},
        		'realtime' => function($realtime = true) {
        			return $realtime;
        		},
        		'summary' => function($summary = true) {
        			return $summary;
        		},
        	),
        	'computed' => array(
        		'url' => function() {
        			return option('sylvainjule.matomo.url');
        		}
        	)
        ),
        'matomo-page' => array(
        	'props' => array(
        		'overview' => function($overview = false) {
        			return $overview;
        		},
        		'period' => function($period = 'month') {
        			return $period;
        		},
                'customuri' => function($customuri = null) {
                    return $customuri;
                },
        	),
        	'computed' => array(
        		'uri' => function() {
                    $customuri = $this->customuri;
        			if(kirby()->multilang()) {
        				$uris = [];
        				foreach(kirby()->languages() as $language) {
        					$code = $language->code();
                            if($customuri && is_array($customuri) && array_key_exists($code, $customuri)) {
            					$uris[$code] = $this->model()->toString($this->customuri[$code]);
                            }
                            else {
                                $uris[$code] = $this->model()->uri($code);
                            }
        				}
        				return $uris;
        			}
        			else {
        				return $this->customuri ? $this->model()->toString($this->customuri) : $this->model()->uri();
        			}
        		},
        		'lang' => function() {
        			return array(
        				'multilang' => kirby()->multilang(),
        				'default'   => kirby()->defaultLanguage() ? kirby()->defaultLanguage()->code() : false,
        				'current'   => kirby()->languageCode() ?? false,
        				'overview'  => $this->overview
        			);
        		}
        	)
        )
    ),
    'translations' => array(
        'en' => require_once __DIR__ . '/lib/languages/en.php',
        'de' => require_once __DIR__ . '/lib/languages/de.php',
        'fr' => require_once __DIR__ . '/lib/languages/fr.php',
        'es' => require_once __DIR__ . '/lib/languages/es.php',
    ),
    'api' => array(
    	'routes' => require_once __DIR__ . '/lib/routes.php',
    ),
));
