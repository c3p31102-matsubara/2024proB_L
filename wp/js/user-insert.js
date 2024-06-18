//@ts-check

// 各種変数は存在するのでエラーは無視
// 叶うならば解決したかったが

import { dataType, insert } from "./APIaccessor.js";
window.addEventListener('load', async (event) => {
    let affiliations = [
        "情報学部学生",
        "健康栄養学部学生",
        "情報学部教員",
        "事務局",
        "その他"
    ]
    let affiliation_number = affiliations.indexOf(form_affiliation) + 1
    console.log(affiliation_number);
    let attribute;
    if (affiliation_number == 1 || affiliation_number == 2) {
        attribute = "student";
    }
    else {
        attribute = "teacher";
    }
    console.log(insert(dataType.userlist, [attribute, "c3p31102", affiliation_number, form_email, form_telephone, form_name]));
})