<?php

namespace SabreForBloonMail\VObject\Component;

use
    SabreForBloonMail\VObject,
    SabreForBloonMail\VObject\Component;

/**
 * The VCalendar component
 *
 * This component adds functionality to a component, specific for a VCALENDAR.
 *
 * @copyright Copyright (C) 2007-2013 fruux GmbH (https://fruux.com/).
 * @author Evert Pot (http://evertpot.com/)
 * @license http://code.google.com/p/sabredav/wiki/License Modified BSD License
 */
class VCalendar extends VObject\Document {

    /**
     * The default name for this component.
     *
     * This should be 'VCALENDAR' or 'VCARD'.
     *
     * @var string
     */
    static public $defaultName = 'VCALENDAR';

    /**
     * This is a list of components, and which classes they should map to.
     *
     * @var array
     */
    static public $componentMap = array(
        'VEVENT'    => 'SabreForBloonMail\\VObject\\Component\\VEvent',
        'VFREEBUSY' => 'SabreForBloonMail\\VObject\\Component\\VFreeBusy',
        'VJOURNAL'  => 'SabreForBloonMail\\VObject\\Component\\VJournal',
        'VTODO'     => 'SabreForBloonMail\\VObject\\Component\\VTodo',
        'VALARM'    => 'SabreForBloonMail\\VObject\\Component\\VAlarm',
    );

    /**
     * List of value-types, and which classes they map to.
     *
     * @var array
     */
    static public $valueMap = array(
        'BINARY'           => 'SabreForBloonMail\\VObject\\Property\\Binary',
        'BOOLEAN'          => 'SabreForBloonMail\\VObject\\Property\\Boolean',
        'CAL-ADDRESS'      => 'SabreForBloonMail\\VObject\\Property\\ICalendar\\CalAddress',
        'DATE'             => 'SabreForBloonMail\\VObject\\Property\\ICalendar\\Date',
        'DATE-TIME'        => 'SabreForBloonMail\\VObject\\Property\\ICalendar\\DateTime',
        'DURATION'         => 'SabreForBloonMail\\VObject\\Property\\ICalendar\\Duration',
        'FLOAT'            => 'SabreForBloonMail\\VObject\\Property\\Float',
        'INTEGER'          => 'SabreForBloonMail\\VObject\\Property\\Integer',
        'PERIOD'           => 'SabreForBloonMail\\VObject\\Property\\ICalendar\\Period',
        'RECUR'            => 'SabreForBloonMail\\VObject\\Property\\ICalendar\\Recur',
        'TEXT'             => 'SabreForBloonMail\\VObject\\Property\\Text',
        'TIME'             => 'SabreForBloonMail\\VObject\\Property\\Time',
        'UNKNOWN'          => 'SabreForBloonMail\\VObject\\Property\\Unknown', // jCard / jCal-only.
        'URI'              => 'SabreForBloonMail\\VObject\\Property\\Uri',
        'UTC-OFFSET'       => 'SabreForBloonMail\\VObject\\Property\\UtcOffset',
    );

    /**
     * List of properties, and which classes they map to.
     *
     * @var array
     */
    static public $propertyMap = array(
        // Calendar properties
        'CALSCALE'      => 'SabreForBloonMail\\VObject\\Property\\FlatText',
        'METHOD'        => 'SabreForBloonMail\\VObject\\Property\\FlatText',
        'PRODID'        => 'SabreForBloonMail\\VObject\\Property\\FlatText',
        'VERSION'       => 'SabreForBloonMail\\VObject\\Property\\FlatText',

        // Component properties
        'ATTACH'            => 'SabreForBloonMail\\VObject\\Property\\Binary',
        'CATEGORIES'        => 'SabreForBloonMail\\VObject\\Property\\Text',
        'CLASS'             => 'SabreForBloonMail\\VObject\\Property\\FlatText',
        'COMMENT'           => 'SabreForBloonMail\\VObject\\Property\\FlatText',
        'DESCRIPTION'       => 'SabreForBloonMail\\VObject\\Property\\FlatText',
        'GEO'               => 'SabreForBloonMail\\VObject\\Property\\Float',
        'LOCATION'          => 'SabreForBloonMail\\VObject\\Property\\FlatText',
        'PERCENT-COMPLETE'  => 'SabreForBloonMail\\VObject\\Property\\Integer',
        'PRIORITY'          => 'SabreForBloonMail\\VObject\\Property\\Integer',
        'RESOURCES'         => 'SabreForBloonMail\\VObject\\Property\\Text',
        'STATUS'            => 'SabreForBloonMail\\VObject\\Property\\FlatText',
        'SUMMARY'           => 'SabreForBloonMail\\VObject\\Property\\FlatText',

        // Date and Time Component Properties
        'COMPLETED'     => 'SabreForBloonMail\\VObject\\Property\\ICalendar\\DateTime',
        'DTEND'         => 'SabreForBloonMail\\VObject\\Property\\ICalendar\\DateTime',
        'DUE'           => 'SabreForBloonMail\\VObject\\Property\\ICalendar\\DateTime',
        'DTSTART'       => 'SabreForBloonMail\\VObject\\Property\\ICalendar\\DateTime',
        'DURATION'      => 'SabreForBloonMail\\VObject\\Property\\ICalendar\\Duration',
        'FREEBUSY'      => 'SabreForBloonMail\\VObject\\Property\\ICalendar\\Period',
        'TRANSP'        => 'SabreForBloonMail\\VObject\\Property\\FlatText',

        // Time Zone Component Properties
        'TZID'          => 'SabreForBloonMail\\VObject\\Property\\FlatText',
        'TZNAME'        => 'SabreForBloonMail\\VObject\\Property\\FlatText',
        'TZOFFSETFROM'  => 'SabreForBloonMail\\VObject\\Property\\UtcOffset',
        'TZOFFSETTO'    => 'SabreForBloonMail\\VObject\\Property\\UtcOffset',
        'TZURL'         => 'SabreForBloonMail\\VObject\\Property\\Uri',

        // Relationship Component Properties
        'ATTENDEE'      => 'SabreForBloonMail\\VObject\\Property\\ICalendar\\CalAddress',
        'CONTACT'       => 'SabreForBloonMail\\VObject\\Property\\FlatText',
        'ORGANIZER'     => 'SabreForBloonMail\\VObject\\Property\\ICalendar\\CalAddress',
        'RECURRENCE-ID' => 'SabreForBloonMail\\VObject\\Property\\ICalendar\\DateTime',
        'RELATED-TO'    => 'SabreForBloonMail\\VObject\\Property\\FlatText',
        'URL'           => 'SabreForBloonMail\\VObject\\Property\\Uri',
        'UID'           => 'SabreForBloonMail\\VObject\\Property\\FlatText',

        // Recurrence Component Properties
        'EXDATE'        => 'SabreForBloonMail\\VObject\\Property\\ICalendar\\DateTime',
        'RDATE'         => 'SabreForBloonMail\\VObject\\Property\\ICalendar\\DateTime',
        'RRULE'         => 'SabreForBloonMail\\VObject\\Property\\ICalendar\\Recur',
        'EXRULE'        => 'SabreForBloonMail\\VObject\\Property\\ICalendar\\Recur', // Deprecated since rfc5545

        // Alarm Component Properties
        'ACTION'        => 'SabreForBloonMail\\VObject\\Property\\FlatText',
        'REPEAT'        => 'SabreForBloonMail\\VObject\\Property\\Integer',
        'TRIGGER'       => 'SabreForBloonMail\\VObject\\Property\\ICalendar\\Duration',

        // Change Management Component Properties
        'CREATED'       => 'SabreForBloonMail\\VObject\\Property\\ICalendar\\DateTime',
        'DTSTAMP'       => 'SabreForBloonMail\\VObject\\Property\\ICalendar\\DateTime',
        'LAST-MODIFIED' => 'SabreForBloonMail\\VObject\\Property\\ICalendar\\DateTime',
        'SEQUENCE'      => 'SabreForBloonMail\\VObject\\Property\\Integer',

        // Request Status
        'REQUEST-STATUS' => 'SabreForBloonMail\\VObject\\Property\\Text',

        // Additions from draft-daboo-valarm-extensions-04
        'ALARM-AGENT'    => 'SabreForBloonMail\\VObject\\Property\\Text',
        'ACKNOWLEDGED'   => 'SabreForBloonMail\\VObject\\Property\\ICalendar\\DateTime',
        'PROXIMITY'      => 'SabreForBloonMail\\VObject\\Property\\Text',
        'DEFAULT-ALARM'  => 'SabreForBloonMail\\VObject\\Property\\Boolean',

    );

    /**
     * Returns the current document type.
     *
     * @return void
     */
    public function getDocumentType() {

        return self::ICALENDAR20;

    }

    /**
     * Returns a list of all 'base components'. For instance, if an Event has
     * a recurrence rule, and one instance is overridden, the overridden event
     * will have the same UID, but will be excluded from this list.
     *
     * VTIMEZONE components will always be excluded.
     *
     * @param string $componentName filter by component name
     * @return array
     */
    public function getBaseComponents($componentName = null) {

        $components = array();
        foreach($this->children as $component) {

            if (!$component instanceof VObject\Component)
                continue;

            if (isset($component->{'RECURRENCE-ID'}))
                continue;

            if ($componentName && $component->name !== strtoupper($componentName))
                continue;

            if ($component->name === 'VTIMEZONE')
                continue;

            $components[] = $component;

        }

        return $components;

    }

    /**
     * If this calendar object, has events with recurrence rules, this method
     * can be used to expand the event into multiple sub-events.
     *
     * Each event will be stripped from it's recurrence information, and only
     * the instances of the event in the specified timerange will be left
     * alone.
     *
     * In addition, this method will cause timezone information to be stripped,
     * and normalized to UTC.
     *
     * This method will alter the VCalendar. This cannot be reversed.
     *
     * This functionality is specifically used by the CalDAV standard. It is
     * possible for clients to request expand events, if they are rather simple
     * clients and do not have the possibility to calculate recurrences.
     *
     * @param DateTime $start
     * @param DateTime $end
     * @return void
     */
    public function expand(\DateTime $start, \DateTime $end) {

        $newEvents = array();

        foreach($this->select('VEVENT') as $key=>$vevent) {

            if (isset($vevent->{'RECURRENCE-ID'})) {
                unset($this->children[$key]);
                continue;
            }


            if (!$vevent->rrule) {
                unset($this->children[$key]);
                if ($vevent->isInTimeRange($start, $end)) {
                    $newEvents[] = $vevent;
                }
                continue;
            }

            $uid = (string)$vevent->uid;
            if (!$uid) {
                throw new \LogicException('Event did not have a UID!');
            }

            $it = new VObject\RecurrenceIterator($this, $vevent->uid);
            $it->fastForward($start);

            while($it->valid() && $it->getDTStart() < $end) {

                if ($it->getDTEnd() > $start) {

                    $newEvents[] = $it->getEventObject();

                }
                $it->next();

            }
            unset($this->children[$key]);

        }

        // Setting all properties to UTC time.
        foreach($newEvents as $newEvent) {

            foreach($newEvent->children as $child) {
                if ($child instanceof VObject\Property\ICalendar\DateTime && $child->hasTime()) {
                    $dt = $child->getDateTimes();
                    // We only need to update the first timezone, because
                    // setDateTimes will match all other timezones to the
                    // first.
                    $dt[0]->setTimeZone(new \DateTimeZone('UTC'));
                    $child->setDateTimes($dt);
                }

            }

            $this->add($newEvent);

        }

        // Removing all VTIMEZONE components
        unset($this->VTIMEZONE);

    }

    /**
     * This method should return a list of default property values.
     *
     * @return array
     */
    protected function getDefaults() {

        return array(
            'VERSION' => '2.0',
            'PRODID' => '-//Sabre//Sabre VObject ' . VObject\Version::VERSION . '//EN',
            'CALSCALE' => 'GREGORIAN',
        );

    }

    /**
     * Validates the node for correctness.
     * An array is returned with warnings.
     *
     * Every item in the array has the following properties:
     *    * level - (number between 1 and 3 with severity information)
     *    * message - (human readable message)
     *    * node - (reference to the offending node)
     *
     * @return array
     */
    public function validate($options = 0) {

        $warnings = array();

        $version = $this->select('VERSION');
        if (count($version)!==1) {
            $warnings[] = array(
                'level' => 1,
                'message' => 'The VERSION property must appear in the VCALENDAR component exactly 1 time',
                'node' => $this,
            );
        } else {
            if ((string)$this->VERSION !== '2.0') {
                $warnings[] = array(
                    'level' => 1,
                    'message' => 'Only iCalendar version 2.0 as defined in rfc5545 is supported.',
                    'node' => $this,
                );
            }
        }
        $version = $this->select('PRODID');
        if (count($version)!==1) {
            $warnings[] = array(
                'level' => 2,
                'message' => 'The PRODID property must appear in the VCALENDAR component exactly 1 time',
                'node' => $this,
            );
        }
        if (count($this->CALSCALE) > 1) {
            $warnings[] = array(
                'level' => 2,
                'message' => 'The CALSCALE property must not be specified more than once.',
                'node' => $this,
            );
        }
        if (count($this->METHOD) > 1) {
            $warnings[] = array(
                'level' => 2,
                'message' => 'The METHOD property must not be specified more than once.',
                'node' => $this,
            );
        }

        $componentsFound = 0;
        foreach($this->children as $child) {
            if($child instanceof Component) {
                $componentsFound++;
            }
        }

        if ($componentsFound===0) {
            $warnings[] = array(
                'level' => 1,
                'message' => 'An iCalendar object must have at least 1 component.',
                'node' => $this,
            );
        }

        return array_merge(
            $warnings,
            parent::validate()
        );

    }

}

