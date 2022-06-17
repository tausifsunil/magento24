<?php
/**
 * Webkul_Grid Grid Interface.
 *
 * @category    Webkul
 *
 * @author      Webkul Software Private Limited
 */
namespace Webkul\Grid\Api\Data;

interface GridInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    const ENTITY_ID = 'entity_id';
    const NAME = 'name';
    const URL = 'url';
    const DESCRIPTION = 'description';
    const IMAGE = 'image';
    const POSITION = 'position';
    const IS_ACTIVE = 'is_active';

    /**
     * Get EntityId.
     *
     * @return int
     */
    public function getEntityId();

    /**
     * Set EntityId.
     */
    public function setEntityId($entityId);

    /**
     * Get Title.
     *
     * @return varchar
     */
    public function getName();

    /**
     * Set Title.
     */
    public function setName($name);

    /**
     * Get Content.
     *
     * @return varchar
     */
    public function getUrl();

    /**
     * Set Content.
     */
    public function setUrl($url);

    /**
     * Get Publish Date.
     *
     * @return varchar
     */
    public function getDescription();

    /**
     * Set PublishDate.
     */
    public function setDescription($description);

    /**
     * Get UpdateTime.
     *
     * @return varchar
     */
    public function getImage();

    /**
     * Set UpdateTime.
     */
    public function setImage($image);

    /**
     * Get CreatedAt.
     *
     * @return varchar
     */
    public function getPosition();

    /**
     * Set CreatedAt.
     */
    public function setPosition($position);

    /**
     * Get IsActive.
     *
     * @return varchar
     */
    public function getIsActive();

    /**
     * Set StartingPrice.
     */
    public function setIsActive($isActive);
}