//@ts-check
const APIURL = "/php/API.php";
/**
 * 
 * @param {*} data 
 * @param {boolean|number} recursive 
 * @param {number} id 
 * @returns 
 */
export async function getjson(data, recursive, id = 1) {
    if (!(Object.values(dataType).includes(data))) {
        console.error("called dataType is undefined");
        return null;
    }
    function post() {
        return jQuery.getJSON(APIURL, {
            type: "json",
            data: data,
            recursive: recursive,
            id: id
        })
    };
    post().done(function (response) {
        return response;
    }
    )
    post().fail(function (response) {
        return response;
    })
    var result = post();
    return result;
}
export async function insert(table, data) {
    if (!Object.values(dataType).includes(table)) {
        console.error("called detaType is undefined");
        return null;
    }
    function post() {
        return jQuery.getJSON(APIURL, {
            type: "insert",
            target: table,
            data: data
        })
    }
}
export const dataType = Object.freeze(
    {
        userlist: "user_l",
        lostitemlist: "lost_l",
        discoverylist: "discovery_l",
        managementlist: "management_l",
        affiliationlist: "affiliation_l",
        user: "user",
        lostitem: "lost",
        discovery: "discovery",
        management: "management",
        affiliation: "affiliation",
    }
)