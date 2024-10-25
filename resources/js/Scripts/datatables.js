// assets/scripts/datatables.js

import { ref } from 'vue';

// Function to get the component for each column
export function getColumnComponent(column)
{
    switch (column.type) {
        case 'text':
            return TextField;
        case 'date':
            return DatePickerField;
        case 'value_integer':
            return ValueInteger;
        case 'link_view_page':
            return LinkViewPage;
            // ... other types
        default:
            return null;
    }
};