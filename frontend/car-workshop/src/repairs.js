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
    ReferenceInput,
    SelectInput,
    ArrayInput, 
    SimpleFormIterator
} from 'react-admin';

export const RepairList = () => (
    <List>
        <Datagrid rowClick="edit">
            <TextField source="name" />
            <EmailField source="email" />
        </Datagrid>
    </List>
);

export const RepairEdit = () => (
    <Edit>
        <SimpleForm>
            <TextInput source="name" />
            <TextInput source="email" />
        </SimpleForm>
    </Edit>
);

export const RepairCreate = () => (
    <Create>
        <SimpleForm>
            <ReferenceInput source="car_id" reference="cars">
               <SelectInput optionText="brand" />
            </ReferenceInput>
            <TextInput source="work_duration" />
            <ArrayInput source="repairServices">
                <SimpleFormIterator>
                    <ReferenceInput source="service_id" reference="services">
                        <SelectInput optionText="name" />
                    </ReferenceInput>
                    <TextInput source="note" />
                </SimpleFormIterator>
            </ArrayInput>
        </SimpleForm>
    </Create>
);