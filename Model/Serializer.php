<?php
/**
 * @author Mavenbird Commerce Team
 * @copyright Copyright (c) 2020 Mavenbird Commerce (https://www.Mavenbird.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mavenbird\Sorting\Model;

use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Unserialize\Unserialize;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Wrapper for Serialize
 * @since 1.1.0
 */
class Serializer
{
    /**
     * @var null|SerializerInterface
     */
    private $serializer;

    /**
     * @var Unserialize
     */
    private $unserialize;

    /**
     * @var Json
     */
    private $jsonSerializer;

    public function __construct(
        ObjectManagerInterface $objectManager,
        Unserialize $unserialize,
        Json $jsonSerializer // replacing PhpSerialize with Json serializer
    ) {
        if (interface_exists(SerializerInterface::class)) {
            // for magento later than 2.2
            $this->serializer = $objectManager->get(SerializerInterface::class);
        }
        $this->unserialize = $unserialize;
        $this->jsonSerializer = $jsonSerializer;
    }

    public function serialize($value)
    {
        try {
            if ($this->serializer === null) {
                return $this->jsonSerializer->serialize($value);
            }

            return $this->serializer->serialize($value);
        } catch (\Exception $e) {
            return '{}';
        }
    }

    public function unserialize($value)
    {
        if (false === $value || null === $value || '' === $value) {
            return false;
        }

        if ($this->serializer === null) {
            return $this->unserialize->unserialize($value);
        }

        try {
            return $this->serializer->unserialize($value);
        } catch (\InvalidArgumentException $exception) {
            return $this->jsonSerializer->unserialize($value);
        }
    }
}
