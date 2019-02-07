<?php

namespace SabreForBloonMail\VObject\Splitter;

/**
 * VObject splitter
 *
 * The splitter is responsible for reading a large vCard or iCalendar object,
 * and splitting it into multiple objects.
 *
 * This is for example for Card and CalDAV, which require every event and vcard
 * to exist in their own objects, instead of one large one.
 *
 * @copyright Copyright (C) 2007-2013 fruux GmbH (https://fruux.com/).
 * @author Dominik Tobschall
 * @license http://code.google.com/p/sabredav/wiki/License Modified BSD License
 */
interface SplitterInterface {

    /**
     * Constructor
     *
     * The splitter should receive an readable file stream as it's input.
     *
     * @param resource $input
     */
    function __construct($input);

    /**
     * Every time getNext() is called, a new object will be parsed, until we
     * hit the end of the stream.
     *
     * When the end is reached, null will be returned.
     *
     * @return SabreForBloonMail\VObject\Component|null
     */
    function getNext();

}