<template>
    <div>
        <div :class="field.group_class">
        <!-- wrap in columns or not -->
            <label
                v-if="field.label"
                :for="field.id"
                :class="field.label_class"
            >
                {{ field.label }}
            </label>
            <input
                v-if="isStandard(field.type)"
                :type="field.type"
                :class="field.class"
                :name="field.key"
                v-bind:value="value"
                v-on:input="$emit('input', $event.target.value)"
            >

            <!-- selects -->
            <!-- checkboxes -->
            <!-- custom? -->
        </div>
    </div>
</template>

<script>
    export default {
        mame: "form-input",
        props: [
            'value',
            'field'
        ],
        created () {

        },
        methods: {
            isStandard (type) {
                return [
                    'hidden',
                    'text',
                    'number',
                    'color',
                    'email',
                    'date',
                    'datetime-local',
                    'month',
                    'range',
                    'search',
                    'tel',
                    'time',
                    'url',
                    'week',
                    'password',
                    'time',
                    'image',
                    'file',
                ].includes(type);
            }
        }
    }
</script>
