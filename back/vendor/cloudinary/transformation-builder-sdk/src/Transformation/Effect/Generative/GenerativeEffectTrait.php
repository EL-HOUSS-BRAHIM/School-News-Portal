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
 * Trait GenerativeEffectTrait
 *
 * @api
 */
trait GenerativeEffectTrait
{
    /**
     * Applies a generative restore effect to the asset.
     *
     * @return GenerativeEffectAction
     */
    public static function generativeRestore()
    {
        return new GenerativeEffectAction(GenerativeEffect::GENERATIVE_RESTORE);
    }

    /**
     * Applies a generative recolor effect to the asset.
     *
     * @param string|array $prompt         Use natural language to describe what you want to affect in the image.
     * @param string       $toColor        The target color.
     * @param bool         $detectMultiple Whether to recolor all instances of the prompt in the image.
     *
     * @return GenerativeRecolor
     */
    public static function generativeRecolor($prompt, $toColor, $detectMultiple = null)
    {
        return new GenerativeRecolor($prompt, $toColor, $detectMultiple);
    }

    /**
     * Applies a generative remove effect to the asset.
     *
     * @param string|array $prompt         Use natural language to describe what you want to affect in the image.
     * @param string|array $region         Remove items from the specified region(s).
     * @param bool         $detectMultiple Whether to detect all instances of the prompt in the image.
     * @param bool         $removeShadow   Whether to remove shadows and reflections.
     *
     * @return GenerativeRemove
     */
    public static function generativeRemove(
        $prompt = null,
        $region = null,
        $detectMultiple = null,
        $removeShadow = null
    ) {
        return new GenerativeRemove($prompt, $region, $detectMultiple, $removeShadow);
    }

    /**
     * Applies a generative replacement effect to the asset.
     *
     * @param string $fromPrompt       Use natural language to describe what you want to replace.
     * @param string $toPrompt         Use natural language to describe the replacement.
     * @param bool   $preserveGeometry Whether to maintain the shape of the object you're replacing.
     * @param bool   $detectMultiple   Whether to detect all instances of the prompt in the image.
     *
     * @return GenerativeReplace
     */
    public static function generativeReplace($fromPrompt, $toPrompt, $preserveGeometry = null, $detectMultiple = null)
    {
        return new GenerativeReplace($fromPrompt, $toPrompt, $preserveGeometry, $detectMultiple);
    }

    /**
     * Uses AI-based prediction to add fine detail while upscaling small images.
     * This 'super-resolution' feature scales each dimension by four, multiplying the total number of pixels by 16.
     *
     * @return GenerativeEffectAction
     */
    public static function upscale()
    {
        return new GenerativeEffectAction(GenerativeEffect::UPSCALE);
    }
}
