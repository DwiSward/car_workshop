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

export const MechanicList = () => (
    <List>
        <Datagrid rowClick="edit">
            <TextField source="name" />
            <EmailField source="email" />
        </Datagrid>
    </List>
);

export const MechanicEdit = () => (
    <Edit>
        <SimpleForm>
            <TextInput source="name" />
            <TextInput source="email" />
        </SimpleForm>
    </Edit>
);

export const MechanicCreate = () => (
    <Create>
        <SimpleForm>
            <TextInput source="name" />
            <TextInput source="email" />
            <PasswordInput source="password" />
            <PasswordInput source="password_confirmation" />
        </SimpleForm>
    </Create>
);