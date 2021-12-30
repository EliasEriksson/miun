import React, {ChangeEvent, useState} from "react";

export const UnitSelect = (props: { name: string, setUnit: (e: ChangeEvent<HTMLSelectElement>) => void }) => {
    const [value, setValue] = useState();
    return (
        <select name={props.name} onChange={e => props.setUnit(e)}>
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