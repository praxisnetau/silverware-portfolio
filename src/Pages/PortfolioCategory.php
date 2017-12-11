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
use SilverWare\Extensions\Lists\ListViewExtension;
use SilverWare\Extensions\Model\ImageDefaultsExtension;
use SilverWare\Forms\FieldSection;
use SilverWare\Lists\ListSource;
use SilverWare\Masonry\Components\MasonryComponent;
use Page;

/**
 * An extension of the page class for a portfolio category.
 *
 * @package SilverWare\Portfolio\Pages
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-portfolio
 */
class PortfolioCategory extends Page implements ListSource
{
    /**
     * Human-readable singular name.
     *
     * @var string
     * @config
     */
    private static $singular_name = 'Portfolio Category';
    
    /**
     * Human-readable plural name.
     *
     * @var string
     * @config
     */
    private static $plural_name = 'Portfolio Categories';
    
    /**
     * Description of this object.
     *
     * @var string
     * @config
     */
    private static $description = 'A category within a portfolio which holds a series of projects';
    
    /**
     * Icon file for this object.
     *
     * @var string
     * @config
     */
    private static $icon = 'silverware/portfolio: admin/client/dist/images/icons/PortfolioCategory.png';
    
    /**
     * Defines the table name to use for this object.
     *
     * @var string
     * @config
     */
    private static $table_name = 'SilverWare_PortfolioCategory';
    
    /**
     * Determines whether this object can exist at the root level.
     *
     * @var boolean
     * @config
     */
    private static $can_be_root = false;
    
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
     * Defines the default values for the fields of this object.
     *
     * @var array
     * @config
     */
    private static $defaults = [
        'ListInherit' => 1,
        'HideFromMainMenu' => 1
    ];
    
    /**
     * Defines the allowed children for this object.
     *
     * @var array|string
     * @config
     */
    private static $allowed_children = [
        PortfolioProject::class
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
                    'PortfolioCategoryOptions',
                    $this->fieldLabel('PortfolioCategory'),
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
        $labels['PortfolioCategory'] = _t(__CLASS__ . '.PORTFOLIOCATEGORY', 'Portfolio Category');
        $labels['DetailsHeading'] = _t(__CLASS__ . '.DETAILSHEADING', 'Details heading');
        
        // Answer Field Labels:
        
        return $labels;
    }
    
    /**
     * Answers a list of projects within the portfolio category.
     *
     * @return DataList
     */
    public function getProjects()
    {
        return PortfolioProject::get()->filter('ParentID', $this->ID);
    }
    
    /**
     * Answers true if the receiver has at least one project.
     *
     * @return boolean
     */
    public function hasProjects()
    {
        return $this->getProjects()->exists();
    }
    
    /**
     * Answers a list of projects within the receiver.
     *
     * @return SS_List
     */
    public function getListItems()
    {
        return $this->getProjects();
    }
    
    /**
     * Answers the text for the details heading.
     *
     * @return string
     */
    public function getDetailsHeadingText()
    {
        return $this->DetailsHeading ? $this->DetailsHeading : $this->getParent()->DetailsHeadingText;
    }
}
