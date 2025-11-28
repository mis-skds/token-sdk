<?php

namespace TokenManagement\SDK\Models;

/**
 * Token model
 * 
 * @package TokenManagement\SDK\Models
 */
class Token
{
    /** @var array */
    private $data;

    /**
     * Create a new token model
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get token ID
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        $id = $this->data['id'] ?? null;
        return $id !== null ? (int) $id : null;
    }

    /**
     * Get token number
     *
     * @return string|null
     */
    public function getTokenNumber(): ?string
    {
        return $this->data['token_number'] ?? null;
    }

    /**
     * Get location ID
     *
     * @return int|null
     */
    public function getLocationId(): ?int
    {
        $id = $this->data['mlocation_id'] ?? null;
        return $id !== null ? (int) $id : null;
    }

    /**
     * Get service point ID
     *
     * @return int|null
     */
    public function getServicePointId(): ?int
    {
        $id = $this->data['mservicepoint_id'] ?? null;
        return $id !== null ? (int) $id : null;
    }

    /**
     * Get token category ID
     *
     * @return int|null
     */
    public function getCategoryId(): ?int
    {
        $id = $this->data['mtokencategory_id'] ?? null;
        return $id !== null ? (int) $id : null;
    }

    /**
     * Get customer name
     *
     * @return string|null
     */
    public function getCustomerName(): ?string
    {
        return $this->data['customer_name'] ?? null;
    }

    /**
     * Get customer phone
     *
     * @return string|null
     */
    public function getCustomerPhone(): ?string
    {
        return $this->data['customer_phone'] ?? null;
    }

    /**
     * Get status
     *
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->data['status'] ?? null;
    }

    /**
     * Get created at timestamp
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->data['created_at'] ?? null;
    }

    /**
     * Get called at timestamp
     *
     * @return string|null
     */
    public function getCalledAt(): ?string
    {
        return $this->data['called_at'] ?? null;
    }

    /**
     * Get served at timestamp
     *
     * @return string|null
     */
    public function getServedAt(): ?string
    {
        return $this->data['served_at'] ?? null;
    }

    /**
     * Get completed at timestamp
     *
     * @return string|null
     */
    public function getCompletedAt(): ?string
    {
        return $this->data['completed_at'] ?? null;
    }

    /**
     * Get all data as array
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }
}
