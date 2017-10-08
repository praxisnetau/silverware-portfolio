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

use SilverStripe\Core\ClassInfo;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DateField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;
use SilverWare\Carousel\Components\CarouselComponent;
use SilverWare\Extensions\Model\DetailFieldsExtension;
use SilverWare\Forms\ToggleGroup;
use SilverWare\Model\Slide;
use Page;

/**
 * An extension of the page class for a portfolio project.
 *
 * @package SilverWare\Portfolio\Pages
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-portfolio
 */
class PortfolioProject extends Page
{
    /**
     * Human-readable singular name.
     *
     * @var string
     * @config
     */
    private static $singular_name = 'Portfolio Project';
    
    /**
     * Human-readable plural name.
     *
     * @var string
     * @config
     */
    private static $plural_name = 'Portfolio Projects';
    
    /**
     * Description of this object.
     *
     * @var string
     * @config
     */
    private static $description = 'An individual project within a portfolio category';
    
    /**
     * Icon file for this object.
     *
     * @var string
     * @config
     */
    private static $icon = 'silverware/portfolio: admin/client/dist/images/icons/PortfolioProject.png';
    
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
        'Completed' => 'Date',
        'ClientName' => 'Varchar(255)',
        'ProjectURL' => 'Varchar(2048)',
        'CompletedFormat' => 'Varchar(255)',
        'ShowCompleted' => 'Boolean',
        'ShowLink' => 'Boolean'
    ];
    
    /**
     * Defines the default values for the fields of this object.
     *
     * @var array
     * @config
     */
    private static $defaults = [
        'ShowInMenus' => 0,
        'ShowCompleted' => 1,
        'ShowLink' => 1
    ];
    
    /**
     * Defines the allowed children for this object.
     *
     * @var array|string
     * @config
     */
    private static $allowed_children = [
        Slide::class
    ];
    
    /**
     * Defines the extension classes to apply to this object.
     *
     * @var array
     * @config
     */
    private static $extensions = [
        DetailFieldsExtension::class
    ];
    
    /**
     * Defines the detail fields to show for the object.
     *
     * @var array
     * @config
     */
    private static $detail_fields = [
        'client' => [
            'name' => 'Client',
            'icon' => 'user',
            'text' => '$ClientName'
        ],
        'completed' => [
            'name' => 'Completed',
            'icon' => 'calendar',
            'text' => '$CompletedFormatted',
            'show' => 'CompletedShown'
        ],
        'project-link' => [
            'name' => 'Link',
            'icon' => 'external-link',
            'text' => '$ProjectLink',
            'show' => 'LinkShown'
        ]
    ];
    
    /**
     * Defines the default format for the completed date.
     *
     * @var string
     * @config
     */
    private static $default_completed_format = 'MMMM Y';
    
    /**
     * Defines the asset folder for uploading images.
     *
     * @var string
     * @config
     */
    private static $meta_image_folder = 'Portfolio';
    
    /**
     * Answers a list of field objects for the CMS interface.
     *
     * @return FieldList
     */
    public function getCMSFields()
    {
        // Obtain Field Objects (from parent):
        
        $fields = parent::getCMSFields();
        
        // Modify Content Field:
        
        if ($content = $fields->fieldByName('Root.Main.Content')) {
            $content->setRows(24);
            $content->setTitle(_t(__CLASS__ . '.OVERVIEW', 'Overview'));
        }
        
        // Create Details Tab:
        
        $fields->findOrMakeTab('Root.Details', $this->fieldLabel('Details'));
        
        // Create Details Fields:
        
        $fields->addFieldsToTab(
            'Root.Details',
            [
                DateField::create(
                    'Completed',
                    $this->fieldLabel('Completed')
                ),
                TextField::create(
                    'ClientName',
                    $this->fieldLabel('ClientName')
                ),
                TextField::create(
                    'ProjectURL',
                    $this->fieldLabel('ProjectURL')
                )
            ]
        );
        
        // Create Options Tab:
        
        $fields->findOrMakeTab('Root.Options', $this->fieldLabel('Options'));
        
        // Create Options Fields:
        
        $fields->addFieldsToTab(
            'Root.Options',
            [
                ToggleGroup::create(
                    'ShowCompleted',
                    $this->fieldLabel('ShowCompleted'),
                    [
                        TextField::create(
                            'CompletedFormat',
                            $this->fieldLabel('CompletedFormat')
                        )
                    ]
                ),
                CheckboxField::create(
                    'ShowLink',
                    $this->fieldLabel('ShowLink')
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
        
        $labels['Title'] = _t(__CLASS__ . '.PROJECTTITLE', 'Project title');
        $labels['Details'] = _t(__CLASS__ . '.DETAILS', 'Details');
        $labels['ShowLink'] = _t(__CLASS__ . '.SHOWLINK', 'Show link');
        $labels['Completed'] = _t(__CLASS__ . '.COMPLETED', 'Completed');
        $labels['ClientName'] = _t(__CLASS__ . '.CLIENTNAME', 'Client name');
        $labels['ProjectURL'] = _t(__CLASS__ . '.PROJECTURL', 'Project URL');
        $labels['ShowCompleted'] = _t(__CLASS__ . '.SHOWCOMPLETED', 'Show completed');
        $labels['CompletedFormat'] = _t(__CLASS__ . '.COMPLETEDDATEFORMAT', 'Completed date format');
        
        // Answer Field Labels:
        
        return $labels;
    }
    
    /**
     * Answers the meta date for the receiver.
     *
     * @return DBDatetime
     */
    public function getMetaDate()
    {
        if ($this->Completed) {
            return $this->dbObject('Completed');
        }
    }
    
    /**
     * Answers a list of the slides for the project.
     *
     * @return DataList
     */
    public function getSlides()
    {
        return $this->AllChildren()->filter('ClassName', ClassInfo::subclassesFor(Slide::class));
    }
    
    /**
     * Answers true if the project has at least one slide.
     *
     * @return boolean
     */
    public function hasSlides()
    {
        return (boolean) $this->getSlides()->exists();
    }
    
    /**
     * Answers a carousel component to render the slides for the template.
     *
     * @return CarouselComponent
     */
    public function getCarousel()
    {
        // Create Carousel Component:
        
        $carousel = CarouselComponent::create()->setParentInstance($this);
        
        // Define Carousel Slides:
        
        $carousel->setSlides($this->getSlides());
        
        // Answer Carousel:
        
        return $carousel;
    }
    
    /**
     * Answers true if the completed date is to be shown.
     *
     * @return boolean
     */
    public function getCompletedShown()
    {
        return ($this->Completed && $this->ShowCompleted);
    }
    
    /**
     * Answers the completed date of the receiver as a formatted string.
     *
     * @param string $format
     *
     * @return string
     */
    public function getCompletedFormatted($format = null)
    {
        return $this->dbObject('Completed')->Format($format ? $format : $this->getDefaultCompletedFormat());
    }
    
    /**
     * Answers the default completed date format.
     *
     * @return string
     */
    public function getDefaultCompletedFormat()
    {
        return $this->CompletedFormat ? $this->CompletedFormat : $this->config()->default_completed_format;
    }
    
    /**
     * Answers true if the project link is to be shown.
     *
     * @return boolean
     */
    public function getLinkShown()
    {
        return ($this->hasProjectLink() && $this->ShowLink);
    }
    
    /**
     * Answers the link for the project.
     *
     * @return string
     */
    public function getProjectLink()
    {
        if ($this->ProjectURL) {
            
            return sprintf(
                '<a href="%1$s" target="_blank">%1$s</a>',
                $this->dbObject('ProjectURL')->URL()
            );
            
        }
    }
    
    /**
     * Answers true if the receiver has a project link.
     *
     * @return boolean
     */
    public function hasProjectLink()
    {
        return (boolean) $this->getProjectLink();
    }
    
    /**
     * Answers the text for the detail fields heading.
     *
     * @return string
     */
    public function getDetailFieldsHeadingText()
    {
        return $this->getParent()->DetailsHeadingText;
    }
}
