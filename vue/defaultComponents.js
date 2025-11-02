import RepeaterControl from "./RepeaterControl.vue";
import EnumControl from "./EnumControl.vue";
import ForeignKeyControl from "./ForeignKeyControl.vue";
import ObjectControl from "./ObjectControl.vue";
import StringArrayControl from "./StringArrayControl.vue";
import IntegrationControl from "./IntegrationControl.vue";

let components = {
    'repeater': RepeaterControl,
    'enum': EnumControl,
    'foreign_id': ForeignKeyControl,
    'object': ObjectControl,
    'string[]': StringArrayControl,
    'integration': IntegrationControl,
}

if(!window.fieldComponents) {
    window.fieldComponents = components;
}

export default components;
