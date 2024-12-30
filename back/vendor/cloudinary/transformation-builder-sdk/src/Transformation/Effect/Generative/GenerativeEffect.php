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
 * Class GenerativeEffect
 */
abstract class GenerativeEffect
{
    const GENERATIVE_RECOLOR = 'gen_recolor';
    const GENERATIVE_REMOVE  = 'gen_remove';
    const GENERATIVE_REPLACE = 'gen_replace';
    const GENERATIVE_RESTORE = 'gen_restore';
    const UPSCALE            = 'upscale';

    use GenerativeEffectTrait;
}
