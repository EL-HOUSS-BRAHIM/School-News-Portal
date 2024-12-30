<?php

namespace Cloudinary\Transformation;

use Cloudinary\TransformationUtils;

/**
 * Trait DetectMultipleTrait
 */
trait DetectMultipleTrait
{
    /**
     * Whether to detect all instances of the prompt in the image.
     *
     * When used with multiple prompts, it’s always true even if not explicitly set.
     *
     * @param bool $detectMultiple Whether to detect multiple objects.
     *
     * @return $this
     */
    public function detectMultiple($detectMultiple = true)
    {
        $this->getMainQualifier()->getPropertiesValue()->setSimpleNamedValue(
            GenerativeEffectAction::MULTIPLE,
            TransformationUtils::boolToString($detectMultiple)
        );

        return $this;
    }
}
