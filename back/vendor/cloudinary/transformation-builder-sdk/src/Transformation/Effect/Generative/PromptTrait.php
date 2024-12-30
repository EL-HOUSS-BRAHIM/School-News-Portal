<?php

namespace Cloudinary\Transformation;

use Cloudinary\ArrayUtils;
use Cloudinary\TransformationUtils;

/**
 * Trait DetectMultipleTrait
 */
trait PromptTrait
{
    /**
     * Use natural language to describe what you want to affect in the image.
     *
     * @param string|array $prompt A list of prompts.
     *
     * @return $this
     */
    public function prompt($prompt)
    {
        $this->getMainQualifier()->getPropertiesValue()->setSimpleNamedValue(
            GenerativeEffectAction::PROMPT,
            new FullListQualifierMultiValue(
                ...ArrayUtils::build($prompt)
            )
        );

        return $this;
    }
}
