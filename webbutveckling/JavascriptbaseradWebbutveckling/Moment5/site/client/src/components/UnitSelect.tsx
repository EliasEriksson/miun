import React, {ChangeEvent, useState} from "react";

export const UnitSelect = (props: { name: string, value: string, event: (e: ChangeEvent<HTMLSelectElement>) => void }) => {
    const [value, setValue] = useState(props.value);
    return (
        <select value={value} name={props.value} onChange={async e => {
            setValue(e.target.value);
            props.event(e);
        }}>
            <option value={"ml"}>ml</option>
            <option value={"cl"}>cl</option>
            <option value={"dl"}>dl</option>
            <option value={"l"}>l</option>
            <option value={"g"}>g</option>
            <option value={"kg"}>kg</option>
            <option value={"st"}>st</option>
        </select>
    );
}