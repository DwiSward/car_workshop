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
    PasswordInput,
    ReferenceInput,
    SelectInput
} from 'react-admin';

export const CarList = () => (
    <List>
        <Datagrid rowClick="edit">
            <TextField source="brand" />
            <TextField source="color" />
            <TextField source="license_plate" />
            <TextField source="type" />
            <TextField source="owner_name" />
        </Datagrid>
    </List>
);

export const CarEdit = () => (
    <Edit>
        <SimpleForm>
            <TextInput source="brand" />
            <TextInput source="color" />
            <TextInput source="license_plate" />
            <TextInput source="type" />
            <ReferenceInput 
                source="customer_id" 
                reference="customers">
                <SelectInput optionText="name" />
            </ReferenceInput>
        </SimpleForm>
    </Edit>
);

export const CarCreate = () => (
    <Create>
        <SimpleForm>
            <TextInput source="brand" />
            <TextInput source="color" />
            <TextInput source="license_plate" />
            <TextInput source="type" />
            <ReferenceInput 
                source="customer_id" 
                reference="customers">
                <SelectInput optionText="name" />
            </ReferenceInput>
        </SimpleForm>
    </Create>
);