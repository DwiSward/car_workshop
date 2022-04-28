import * as React from "react";
import { 
    List, 
    Datagrid, 
    TextField, 
    EmailField, 
    Edit,
    SimpleForm,
    TextInput,
    Create,
    PasswordInput 
} from 'react-admin';

export const ServiceList = () => (
    <List>
        <Datagrid rowClick="edit">
            <TextField source="name" />
            <TextField source="price" />
        </Datagrid>
    </List>
);

export const ServiceEdit = () => (
    <Edit>
        <SimpleForm>
            <TextInput source="name" />
            <TextInput source="price" />
        </SimpleForm>
    </Edit>
);

export const ServiceCreate = () => (
    <Create>
        <SimpleForm>
            <TextInput source="name" />
            <TextInput source="price" />
        </SimpleForm>
    </Create>
);