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

export const RepairList = () => (
    <List>
        <Datagrid rowClick="edit">
            <TextField source="owner_name" />
            <TextField source="created_at_text" />
            <TextField source="status_of_services" />
            <TextField source="status_text" />
        </Datagrid>
    </List>
);

export const RepairEdit = () => (
    <Edit>
        <Form>
            <TextInput source="owner_name" editable="false" />
            <TextInput source="car_brand" editable="false" />
            <TextInput source="work_duration" editable="false" />
            <TextInput source="total" editable="false" />
            <SelectInput source="status" choices={[
                { id: 0, name: 'New' },
                { id: 1, name: 'Approved' },
                { id: 5, name: 'Cancel' },
            ]} />
            <ArrayField source="repair_services">
                <Datagrid>
                    <TextField source="service_name" />
                    <TextField source="price" />
                </Datagrid>
            </ArrayField>
            <SaveButton />
        </Form>
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
                    <NumberInput source="qty" />
                    <TextInput source="note" />
                </SimpleFormIterator>
            </ArrayInput>
        </SimpleForm>
    </Create>
);