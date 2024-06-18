//@ts-check
import { getjson, dataType } from "./APIaccessor.js";
window.addEventListener('load', async (event) => {
    let lostitemlist = await getjson(dataType.lostitemlist, false, 0);
    console.log(lostitemlist["status"]);
    let table = document.getElementById("tablebody");
    lostitemlist["body"].forEach(element => {
        console.log(element);
        console.log(element["ID"]);
        console.log(element["category"]);
        table?.appendChild(CreateCard(element["category"], element["place"], element["color"], element["features"]));
        function CreateCard(category, where, color, feature) {
            let tr = document.createElement("tr");
            {
                let td1 = document.createElement("td");
                td1.innerText = category;
                let td2 = document.createElement("td");
                td2.innerText = where;
                let td3 = document.createElement("td");
                td3.innerText = color;
                let td4 = document.createElement("td");
                td4.innerText = feature;
                tr.appendChild(td1);
                tr.appendChild(td2);
                tr.appendChild(td3);
                tr.appendChild(td4);
            }
            return tr;
        }
    });
})