<?php

namespace App\Objects;

/**
 *
 */
class WebhookData
{
    private $object_type;
    private $object_id;
    private $aspect_type;
    private $owner_id;
    private $subscription_id;
    private $event_time;
    private Updates $updates;

    /**
     * @param $object_type - Always either "activity" or "athlete."
     * @param $object_id - For activity events, the activity's ID. For athlete events, the athlete's ID.
     * @param $aspect_type - Always "create," "update," or "delete."
     * @param $owner_id - The athlete's ID.
     * @param $subscription_id - The push subscription ID that is receiving this event.
     * @param $event_time - The time that the event occurred.
     * @param $updates - For activity update events, keys can contain "title," "type," and "private," which is always "true" (activity visibility set to Only You) or "false" (activity visibility set to Followers Only or Everyone). For app deauthorization events, there is always an "authorized" : "false" key-value pair.
     */
    function __construct($object_type, $object_id, $aspect_type, $owner_id, $subscription_id, $event_time, $updates)
    {
        $this->object_type = $object_type;
        $this->object_id = $object_id;
        $this->aspect_type = $aspect_type;
        $this->owner_id = $owner_id;
        $this->subscription_id = $subscription_id;
        $this->event_time = $event_time;
        $this->updates = $updates;
    }

    /**
     * @return mixed
     */
    public function getObjectType(): string
    {
        return $this->object_type;
    }

    /**
     * @return mixed
     */
    public function getObjectId()
    {
        return $this->object_id;
    }

    /**
     * @return mixed
     */
    public function getAspectType(): string
    {
        return $this->aspect_type;
    }

    /**
     * @return mixed
     */
    public function getOwnerId()
    {
        return $this->owner_id;
    }

    /**
     * @return mixed
     */
    public function getSubscriptionId()
    {
        return $this->subscription_id;
    }

    /**
     * @return mixed
     */
    public function getEventTime()
    {
        return $this->event_time;
    }

    /**
     * @return Updates
     */
    public function getUpdates(): Updates
    {
        return $this->updates;
    }

}