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
    SimpleFormIterator,
    Form,
    ArrayField,
    SaveButton,
    NumberInput,
    SelectField
} from 'react-admin';

export const RepairMechanicList = () => (
    <List>
        <Datagrid rowClick="edit">
            <TextField source="car_brand" />
            <TextField source="car_license_plate" />
            <TextField source="car_color" />
            <TextField source="car_type" />
            <TextField source="service_name" />
            <TextField source="partner" />
        </Datagrid>
    </List>
);

export const RepairMechanicEdit = () => (
    <Edit>
        <Form>
            <TextInput source="car_brand" editable="false" />
            <TextInput source="car_license_plate" editable="false" />
            <TextInput source="service_name" editable="false" />
            <TextInput source="qty" editable="false" />
            <SelectInput source="status" choices={[
                { id: 1, name: 'On Progress' },
                { id: 2, name: 'Done' },
            ]} />
            <SaveButton />
        </Form>
    </Edit>
);

export const RepairMechanicCreate = () => (
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
                    <NumberInput source="qty" />
                    <TextInput source="note" />
                </SimpleFormIterator>
            </ArrayInput>
        </SimpleForm>
    </Create>
);