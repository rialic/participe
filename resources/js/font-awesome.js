import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'


// import {} from '@fortawesome/free-brands-svg-icons'

import {
    fas,
    faEnvelope,
    faUser,
    faCircleCheck,
    faCircleRight,
    faCircleXmark,
    faPlus,
    faMagnifyingGlass,
    faTrash,
    faArrowDown,
    faFilePen,
    faPaperPlane,
} from '@fortawesome/free-solid-svg-icons'

import {
    far,
    faSquareCheck,
    faFileLines
} from '@fortawesome/free-regular-svg-icons'

library.add(
    fas,
    far,
    faEnvelope,
    faSquareCheck,
    faUser,
    faCircleCheck,
    faCircleXmark,
    faPlus,
    faCircleRight,
    faMagnifyingGlass,
    faTrash,
    faFileLines,
    faArrowDown,
    faFilePen
)

export default FontAwesomeIcon