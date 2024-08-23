<?php
/**
 * @author Mavenbird Commerce Team
 * @copyright Copyright (c) 2020 Mavenbird Commerce (https://www.Mavenbird.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Mavenbird\Sorting\Model;

/**
 * Class MagentoVersion is used for faster retrieving magento version
 */
class MagentoVersion
{
    const MAGENTO_VERSION = 'amasty_magento_version';

    /**
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    private $productMetadata;

    /**
     * @var \Magento\Framework\App\Cache\Type\Config
     */
    private $cache;

    /**
     * @var string
     */
    private $magentoVersion;

    public function __construct(
        \Magento\Framework\App\Cache\Type\Config $cache,
        \Magento\Framework\App\ProductMetadataInterface $productMetadata
    ) {
        $this->productMetadata = $productMetadata;
        $this->cache = $cache;
    }

    /**
     * @return string
     */
    public function get()
    {
        if (!$this->magentoVersion
            && !($this->magentoVersion = $this->cache->load(self::MAGENTO_VERSION))
        ) {
            $this->magentoVersion = $this->productMetadata->getVersion();
            $this->cache->save($this->magentoVersion, self::MAGENTO_VERSION);
        }

        return $this->magentoVersion;
    }
}
