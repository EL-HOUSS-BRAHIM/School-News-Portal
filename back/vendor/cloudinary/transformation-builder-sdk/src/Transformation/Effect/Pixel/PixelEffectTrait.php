<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation;

/**
 * Trait PixelEffectTrait
 *
 * @api
 */
trait PixelEffectTrait
{
    /**
     * Applies a blurring filter to the asset.
     *
     * @param int $strength The strength of the blur. (Range: 1 to 2000, Server default: 100)
     *
     * @return Blur
     */
    public static function blur($strength = null)
    {
        return new Blur($strength);
    }

    /**
     * Applies a blurring filter to the faces detected on the asset.
     *
     * @param int $strength The strength of the blur. (Range: 1 to 2000, Server default: 100)
     *
     * @return Blur
     */
    public static function blurFaces($strength = null)
    {
        return (new Blur($strength))->region(Region::faces());
    }

    /**
     * Applies a blurring filter to the region on the asset.
     *
     * @param int|string $strength The strength of the blur. (Range: 1 to 2000, Server default: 100)
     * @param int|string $x        X.
     * @param int|string $y        Y.
     * @param int|string $width    Width.
     * @param int|string $height   Height.
     *
     * @return Blur
     */
    public static function blurRegion($strength = null, $x = null, $y = null, $width = null, $height = null)
    {
        return (new Blur($strength))->region(Region::custom($x, $y, $width, $height));
    }

    /**
     * Applies a vignette effect.
     *
     * @param int $strength The strength of the vignette. (Range: 0 to 100, Server default: 20)
     *
     * @return StrengthEffectAction
     */
    public static function vignette($strength = null)
    {
        return EffectAction::withStrength(PixelEffect::VIGNETTE, EffectRange::PERCENT, $strength);
    }
}
