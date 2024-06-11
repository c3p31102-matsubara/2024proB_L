//@ts-check
const APIURL = "/php/API.php";
/**
 * 
 * @param {*} data 
 * @param {boolean|number} recursive 
 * @param {number} id 
 * @returns 
 */
export async function Post(data, recursive, id = 1) {
    if (!(Object.values(dataType).includes(data))) {
        console.error("called dataType is undefined");
        return null;
    }
    function getjson() {
        return jQuery.getJSON(APIURL, {
            type: "json",
            data: data,
            recursive: recursive,
            id: id
        })
    };
    getjson().done(function (response) {
        return response;
    }
    )
    getjson().fail(function (response) {
        return response;
    })
    var result = getjson();
    return result;
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