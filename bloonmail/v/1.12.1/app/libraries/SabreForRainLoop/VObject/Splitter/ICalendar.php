<?php

namespace SabreForBloonMail\VObject\Splitter;

use
    SabreForBloonMail\VObject,
    SabreForBloonMail\VObject\Component\VCalendar;

/**
 * Splitter
 *
 * This class is responsible for splitting up iCalendar objects.
 *
 * This class expects a single VCALENDAR object with one or more
 * calendar-objects inside. Objects with identical UID's will be combined into
 * a single object.
 *
 * @copyright Copyright (C) 2007-2013 fruux GmbH (https://fruux.com/).
 * @author Dominik Tobschall
 * @author Armin Hackmann
 * @license http://code.google.com/p/sabredav/wiki/License Modified BSD License
 */
class ICalendar implements SplitterInterface {

    /**
     * Timezones
     *
     * @var array
     */
    protected $vtimezones = array();

    /**
     * iCalendar objects
     *
     * @var array
     */
    protected $objects = array();

    /**
     * Constructor
     *
     * The splitter should receive an readable file stream as it's input.
     *
     * @param resource $input
     * @param int $options Parser options, see the OPTIONS constants.
     */
    public function __construct($input, $options = 0) {

        $data = VObject\Reader::read($input, $options);
        $vtimezones = array();
        $components = array();

        foreach($data->children() as $component) {
            if (!$component instanceof VObject\Component) {
                continue;
            }

            // Get all timezones
            if ($component->name === 'VTIMEZONE') {
                $this->vtimezones[(string)$component->TZID] = $component;
                continue;
            }

            // Get component UID for recurring Events search
            if($component->UID) {
                $uid = (string)$component->UID;
            } else {
                // Generating a random UID
                $uid = sha1(microtime()) . '-vobjectimport';
            }

            // Take care of recurring events
            if (!array_key_exists($uid, $this->objects)) {
                $this->objects[$uid] = new VCalendar();
            }

            $this->objects[$uid]->add(clone $component);
        }

    }

    /**
     * Every time getNext() is called, a new object will be parsed, until we
     * hit the end of the stream.
     *
     * When the end is reached, null will be returned.
     *
     * @return SabreForBloonMail\VObject\Component|null
     */
    public function getNext() {

        if($object=array_shift($this->objects)) {

            // create our baseobject
            $object->version = '2.0';
            $object->prodid = '-//Sabre//Sabre VObject ' . VObject\Version::VERSION . '//EN';
            $object->calscale = 'GREGORIAN';

            // add vtimezone information to obj (if we have it)
            foreach ($this->vtimezones as $vtimezone) {
                $object->add($vtimezone);
            }

            return $object;

        } else {

            return null;

        }

   }

}
