# SilverWare Portfolio Module

[![Latest Stable Version](https://poser.pugx.org/silverware/portfolio/v/stable)](https://packagist.org/packages/silverware/portfolio)
[![Latest Unstable Version](https://poser.pugx.org/silverware/portfolio/v/unstable)](https://packagist.org/packages/silverware/portfolio)
[![License](https://poser.pugx.org/silverware/portfolio/license)](https://packagist.org/packages/silverware/portfolio)

Provides a portfolio for [SilverWare][silverware] apps, divided into a series of categories and projects.

## Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
- [Issues](#issues)
- [Contribution](#contribution)
- [Maintainers](#maintainers)
- [License](#license)

## Requirements

- [SilverWare][silverware]
- [SilverWare Carousel][silverware-carousel]
- [SilverWare Masonry][silverware-masonry]

## Installation

Installation is via [Composer][composer]:

```
$ composer require silverware/portfolio
```

## Usage

The module provides three pages ready for use within the CMS:

- `Portfolio`
- `PortfolioCategory`
- `PortfolioProject`

Create a `Portfolio` page as the top-level of your portfolio. Under the `Portfolio` you
may add `PortfolioCategory` pages as children to divide the portfolio into a series
of categories. Then, as children of `PortfolioCategory`, add your `PortfolioProject` pages.

You may add a series of `Slide` objects as children of `PortfolioProject` pages. These slides
will be rendered using a `CarouselComponent` on each project page.

`Portfolio` and `PortfolioCategory` pages are also implementors of `ListSource`, and can be used with
components to show a series of portfolio projects as list items.

## Issues

Please use the [GitHub issue tracker][issues] for bug reports and feature requests.

## Contribution

Your contributions are gladly welcomed to help make this project better.
Please see [contributing](CONTRIBUTING.md) for more information.

## Maintainers

[![Colin Tucker](https://avatars3.githubusercontent.com/u/1853705?s=144)](https://github.com/colintucker) | [![Praxis Interactive](https://avatars2.githubusercontent.com/u/1782612?s=144)](http://www.praxis.net.au)
---|---
[Colin Tucker](https://github.com/colintucker) | [Praxis Interactive](http://www.praxis.net.au)

## License

[BSD-3-Clause](LICENSE.md) &copy; Praxis Interactive

[silverware]: https://github.com/praxisnetau/silverware
[silverware-carousel]: https://github.com/praxisnetau/silverware-carousel
[silverware-masonry]: https://github.com/praxisnetau/silverware-masonry
[composer]: https://getcomposer.org
[issues]: https://github.com/praxisnetau/silverware-portfolio/issues
