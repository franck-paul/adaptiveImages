<?php
/**
 * @package Dotclear
 *
 * @copyright Olivier Meunier & Association Dotclear
 * @copyright GPL-2.0-only
 */
#
# DOT NOT MODIFY THIS FILE !
#

use Dotclear\Helper\L10n;

L10n::$locales['Implements the 3-layers technique for Adaptive Images generation (by Nursit)'] = 'Implémente la technique à 3 couches pour les images adaptatives (par Nursit)';
L10n::$locales['adaptiveImages'] = 'adaptiveImages';
L10n::$locales['Adaptive Images'] = 'Images adaptatives';
L10n::$locales['Enable Adaptive Images'] = 'Activer les images adaptatives';
L10n::$locales['Options'] = 'Options';
L10n::$locales['Default maximum display width for images:'] = 'Largeur maximum des images affichées:';
L10n::$locales['The Default maximum display width for images is 640 pixels.'] = 'La largeur maximum des images affichées est de 640 pixels par défaut.';
L10n::$locales['Default minimum display width for images:'] = 'Largeur minimum des images traitées:';
L10n::$locales['Smaller images will be unchanged (320 pixels by default).'] = 'Les images plus petites resteront inchangées (320 pixels par défaut).';
L10n::$locales['Backgound color for JPG images produced from transparent one:'] = 'Couleur de fond pour les images JPG produites à partir de transparentes :';
L10n::$locales['Usually the same background color as your blog (#ffffff by default).'] = 'En général la même que la couleur de fond de votre blog (#ffffff par défaut).';
L10n::$locales['JPEG compression quality (0 to 100):'] = 'Qualité de la compression JPEG (0 à 100) :';
L10n::$locales['Preview images (10 by default):'] = '(10 par défaut)';
L10n::$locales['Standard images (75 by default):'] = 'Images simples :';
L10n::$locales['1.5x images (65 by default):'] = '(65 par défaut)';
L10n::$locales['2x images: (45 by default)'] = '(45 par défaut)';
L10n::$locales['Advanced options'] = 'Options avancées';
L10n::$locales['Deliver adaptive images on demand'] = 'Délivrer les images adaptatives à la demande';
L10n::$locales['Warning, this option needs an Apache RewriteRule or equivalent (see README.md)!'] = 'Attention, cette option nécessite une directive \'RewriteRule\' Apache ou équivalent (voir README.md) !';
L10n::$locales['Breakpoints (in pixel) for image generation (comma saparated values):'] = 'Seuils (en pixel) pour les variantes d\'images (valeurs séparées par des virgules) :';
L10n::$locales['By default: 160,320,480,640,960,1440.'] = 'Par défaut : 160,320,480,640,960,1440.';
L10n::$locales['Empty adaptive images cache directory'] = 'Vider le cache des images adaptatives';
L10n::$locales['Adaptive images cache directory emptied.'] = 'Le cache des images adaptatives a été vidé.';
L10n::$locales['Failed to empty adaptive images cache directory.'] = 'Impossible de vider le cache des images adaptatives.';
L10n::$locales['It may be useful to empty this cache when modifying breakpoints or quality of JPEG compression. Notice : with some hosters, the adaptive images cache cannot be emptied with this plugin.'] = 'Il peut être utile de vider ce cache si vous modifiez les seuils ou les qualités de compression JPEG. Note : avec certains hébergeurs, le cache des images adaptatives ne pourra être vidé avec ce plugin.';
