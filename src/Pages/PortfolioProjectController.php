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

use PageController;

/**
 * An extension of the page controller class for a portfolio project controller.
 *
 * @package SilverWare\Portfolio\Pages
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-portfolio
 */
class PortfolioProjectController extends PageController
{
    /**
     * Answers an SSViewer instance with hash link rewriting disabled (for carousel controls).
     *
     * @param $action string
     *
     * @return SSViewer
     */
    public function getViewer($action)
    {
        return parent::getViewer($action)->dontRewriteHashlinks();
    }
}
