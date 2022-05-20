import { AbstractEvent, Modals } from 'hamtaraw';
import $ from "jquery";

/**
 * Close the modal when clicking on the hamtaraw backdrop.
 *
 * @author Phil'dy Jocelyn Belcou <pj.belcou@gmail.com>
 */
export default class HideWhenClicked extends AbstractEvent {
    /**
     * @inheritDoc
     * @see AbstractEvent.getEvent
     */
    getEvent() {
        return 'click';
    }

    /**
     * @inheritDoc
     * @see AbstractEvent.getSelector
     */
    getSelector() {
        return '.hamtaraw-modal';
    }

    /**
     * @inheritDoc
     * @see AbstractEvent.handler
     */
    handler(event, element) {
        if (!$(event.target).parents('.modal-content').length) {
            Modals.closeCurrent();
        }
    }
}