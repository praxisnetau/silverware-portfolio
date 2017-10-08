<?php

/**
 * This file is part of SilverWare.
 *
 * PHP version >=5.6.0
 *
 * For full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package SilverWare\Portfolio\Pages
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-portfolio
 */

namespace SilverWare\Portfolio\Pages;

use SilverStripe\Forms\TextField;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;
use SilverWare\Extensions\Lists\ListViewExtension;
use SilverWare\Extensions\Model\ImageDefaultsExtension;
use SilverWare\Forms\FieldSection;
use SilverWare\Lists\ListSource;
use SilverWare\Masonry\Components\MasonryComponent;
use Page;

/**
 * An extension of the page class for a portfolio.
 *
 * @package SilverWare\Portfolio\Pages
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-portfolio
 */
class Portfolio extends Page implements ListSource
{
    /**
     * Human-readable singular name.
     *
     * @var string
     * @config
     */
    private static $singular_name = 'Portfolio';
    
    /**
     * Human-readable plural name.
     *
     * @var string
     * @config
     */
    private static $plural_name = 'Portfolios';
    
    /**
     * Description of this object.
     *
     * @var string
     * @config
     */
    private static $description = 'Holds a series of portfolio projects organised into categories';
    
    /**
     * Icon file for this object.
     *
     * @var string
     * @config
     */
    private static $icon = 'silverware/portfolio: admin/client/dist/images/icons/Portfolio.png';
    
    /**
     * Defines the default child class for this object.
     *
     * @var string
     * @config
     */
    private static $default_child = PortfolioCategory::class;
    
    /**
     * Maps field names to field types for this object.
     *
     * @var array
     * @config
     */
    private static $db = [
        'DetailsHeading' => 'Varchar(128)'
    ];
    
    /**
     * Defines the default values for the list view component.
     *
     * @var array
     * @config
     */
    private static $list_view_defaults = [
        'ShowImage' => 'all',
        'ShowHeader' => 'all',
        'ShowDetails' => 'none',
        'ShowSummary' => 'none',
        'ShowContent' => 'none',
        'ShowFooter' => 'none',
        'ImageLinksTo' => 'item',
        'ImageItems' => 1,
        'LinkTitles' => 1,
        'LinkImages' => 1,
        'OverlayImages' => 1,
        'Gutter' => 10,
        'ColumnUnit' => 'percent',
        'PercentWidth' => [
            'Tiny' => '100',
            'Small' => '50',
            'Medium' => '33.33333333',
            'Large' => '33.33333333',
            'Huge' => '33.33333333'
        ],
        'HorizontalOrder' => 1,
        'ImageItems' => 1
    ];
    
    /**
     * Defines the allowed children for this object.
     *
     * @var array|string
     * @config
     */
    private static $allowed_children = [
        PortfolioCategory::class
    ];
    
    /**
     * Defines the extension classes to apply to this object.
     *
     * @var array
     * @config
     */
    private static $extensions = [
        ListViewExtension::class,
        ImageDefaultsExtension::class
    ];
    
    /**
     * Defines the list component class to use.
     *
     * @var string
     * @config
     */
    private static $list_component_class = MasonryComponent::class;
    
    /**
     * Answers a list of field objects for the CMS interface.
     *
     * @return FieldList
     */
    public function getCMSFields()
    {
        // Obtain Field Objects (from parent):
        
        $fields = parent::getCMSFields();
        
        // Create Options Tab:
        
        $fields->findOrMakeTab('Root.Options', $this->fieldLabel('Options'));
        
        // Create Options Fields:
        
        $fields->addFieldsToTab(
            'Root.Options',
            [
                FieldSection::create(
                    'PortfolioOptions',
                    $this->fieldLabel('Portfolio'),
                    [
                        TextField::create(
                            'DetailsHeading',
                            $this->fieldLabel('DetailsHeading')
                        )
                    ]
                )
            ]
        );
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Answers the labels for the fields of the receiver.
     *
     * @param boolean $includerelations Include labels for relations.
     *
     * @return array
     */
    public function fieldLabels($includerelations = true)
    {
        // Obtain Field Labels (from parent):
        
        $labels = parent::fieldLabels($includerelations);
        
        // Define Field Labels:
        
        $labels['Options'] = _t(__CLASS__ . '.OPTIONS', 'Options');
        $labels['Portfolio'] = _t(__CLASS__ . '.PORTFOLIO', 'Portfolio');
        $labels['DetailsHeading'] = _t(__CLASS__ . '.DETAILSHEADING', 'Details heading');
        
        // Answer Field Labels:
        
        return $labels;
    }
    
    /**
     * Answers a list of projects within the portfolio.
     *
     * @return DataList
     */
    public function getProjects()
    {
        return PortfolioProject::get()->filter('ParentID', $this->AllChildren()->column('ID') ?: null);
    }
    
    /**
     * Answers a list of projects within the receiver.
     *
     * @return DataList
     */
    public function getListItems()
    {
        return $this->getProjects();
    }
    
    /**
     * Answers all categories within the receiver.
     *
     * @return DataList
     */
    public function getAllCategories()
    {
        return $this->AllChildren()->filter('ClassName', PortfolioCategory::class);
    }
    
    /**
     * Answers all visible categories within the receiver.
     *
     * @return ArrayList
     */
    public function getVisibleCategories()
    {
        $categories = $this->getAllCategories()->filterByCallback(function ($category) {
            return $category->hasProjects();
        });
        
        $data = ArrayList::create();
        
        foreach ($categories as $category) {
            
            $data->push(
                ArrayData::create([
                    'Title' => $category->Title,
                    'Projects' => $this->getProjectList($category)
                ])
            );
            
        }
        
        return $data;
    }
    
    /**
     * Answers the project list component for the template.
     *
     * @return MasonryComponent
     */
    public function getProjectList(PortfolioCategory $category)
    {
        $list = clone $this->getListComponent();
        
        $list->setSource($category->getProjects());
        $list->setStyleIDFrom($this, $category->Title);
        
        return $list;
    }
    
    /**
     * Answers the text for the details heading.
     *
     * @return string
     */
    public function getDetailsHeadingText()
    {
        return $this->DetailsHeading ? $this->DetailsHeading : _t(__CLASS__ . '.DETAILS', 'Details');
    }
    
    /**
     * Answers a message string to be shown when no data is available.
     *
     * @return string
     */
    public function getNoDataMessage()
    {
        return _t(__CLASS__ . '.NODATAAVAILABLE', 'No data available.');
    }
}
