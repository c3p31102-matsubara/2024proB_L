//@ts-check
let APIURL = "/php/API.php";
export async function Post(data, recursive, id = 1) {
    if (!(Object.values(dataType).includes(data))) {
        console.error("called dataType is undefined");
        return null;
    }
    function getjson() {
        return jQuery.getJSON(APIURL, {
            type: "json",
            data: data,
            id: id,
            recursive: recursive
        }
        )
    };
    getjson().done(function (response) {
        return response;
    }
    )
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