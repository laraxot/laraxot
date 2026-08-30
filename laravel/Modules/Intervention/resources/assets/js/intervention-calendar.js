import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import listPlugin from '@fullcalendar/list';
import timeGridPlugin from '@fullcalendar/timegrid';

// Import all locales so we can set locale: 'it' by code.
import allLocales from '@fullcalendar/core/locales-all';

window.FullCalendar = {
    Calendar,
    dayGridPlugin,
    timeGridPlugin,
    listPlugin,
    interactionPlugin,
    allLocales,
};

window.InterventionCalendarTheme = {
    hexToRgba(hex, alpha) {
        if (typeof hex !== 'string') {
            return null
        }

        const normalized = hex.trim().replace('#', '')

        if (normalized.length !== 6) {
            return null
        }

        const red = parseInt(normalized.slice(0, 2), 16)
        const green = parseInt(normalized.slice(2, 4), 16)
        const blue = parseInt(normalized.slice(4, 6), 16)

        if (Number.isNaN(red) || Number.isNaN(green) || Number.isNaN(blue)) {
            return null
        }

        return `rgba(${red}, ${green}, ${blue}, ${alpha})`
    },

    isDarkColor(hex) {
        if (typeof hex !== 'string') {
            return false
        }

        const normalized = hex.trim().replace('#', '')

        if (normalized.length !== 6) {
            return false
        }

        const red = parseInt(normalized.slice(0, 2), 16)
        const green = parseInt(normalized.slice(2, 4), 16)
        const blue = parseInt(normalized.slice(4, 6), 16)

        if (Number.isNaN(red) || Number.isNaN(green) || Number.isNaN(blue)) {
            return false
        }

        const luminance = (0.299 * red) + (0.587 * green) + (0.114 * blue)

        return luminance < 140
    },

    getReadableTextColor(hex) {
        return this.isDarkColor(hex) ? '#ffffff' : '#111827'
    },

    applyEventAppearance(info) {
        const eventElement = info.el
        const eventMain = eventElement.querySelector('.fc-event-main')
        const operatorHex = info.event.extendedProps?.color || info.event.backgroundColor || '#2563eb'
        const textColor = info.event.textColor || info.event.extendedProps?.text_color || this.getReadableTextColor(operatorHex)
        const borderColor = info.event.borderColor || operatorHex
        const viewType = info.view.type

        eventElement.style.borderRadius = '8px'
        eventElement.style.boxShadow = 'none'

        if (viewType === 'timeGridWeek' || viewType === 'timeGridDay') {
            eventElement.style.backgroundColor = operatorHex
            eventElement.style.borderColor = borderColor
            eventElement.style.borderLeftWidth = '4px'
            eventElement.style.borderTopWidth = '1px'
            eventElement.style.borderRightWidth = '1px'
            eventElement.style.borderBottomWidth = '1px'
            eventElement.style.color = textColor

            if (eventMain) {
                eventMain.style.color = textColor
                eventMain.style.fontWeight = '600'
            }

            return
        }

        if (viewType === 'listWeek') {
            const softBackground = this.hexToRgba(operatorHex, 0.14)

            if (softBackground) {
                eventElement.style.backgroundColor = softBackground
            }

            eventElement.style.borderLeftWidth = '4px'
            eventElement.style.borderLeftColor = borderColor
            eventElement.style.borderTopWidth = '0'
            eventElement.style.borderRightWidth = '0'
            eventElement.style.borderBottomWidth = '0'
            eventElement.style.color = '#111827'

            if (eventMain) {
                eventMain.style.color = '#111827'
            }

            return
        }

        const softBackground = this.hexToRgba(operatorHex, 0.18)

        if (softBackground) {
            eventElement.style.backgroundColor = softBackground
        }

        eventElement.style.borderLeftWidth = '4px'
        eventElement.style.borderLeftColor = borderColor
        eventElement.style.borderTopWidth = '0'
        eventElement.style.borderRightWidth = '0'
        eventElement.style.borderBottomWidth = '0'
        eventElement.style.color = '#111827'

        if (eventMain) {
            eventMain.style.color = '#111827'
            eventMain.style.fontWeight = '500'
        }
    },
}

