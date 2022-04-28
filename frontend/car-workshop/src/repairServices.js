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

export const RepairServiceList = () => (
    <List>
        <Datagrid rowClick="edit">
            <TextField source="car_brand" />
            <TextField source="car_license_plate" />
            <TextField source="car_color" />
            <TextField source="car_type" />
            <TextField source="service_name" />
        </Datagrid>
    </List>
);

export const RepairServiceEdit = () => (
    <Edit>
        <Form>
            <TextInput source="car_brand" editable="false" />
            <TextInput source="car_license_plate" editable="false" />
            <ArrayInput source="repairServices">
                <SimpleFormIterator>
                    <ReferenceInput source="mechanic_id" reference="mechanics">
                        <SelectInput optionText="name" />
                    </ReferenceInput>
                </SimpleFormIterator>
            </ArrayInput>
            <SaveButton />
        </Form>
    </Edit>
);

export const RepairServiceCreate = () => (
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