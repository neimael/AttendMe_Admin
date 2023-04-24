import {

  mdiMonitor,
  mdiLock,
  mdiViewList,
  mdiHome, mdiAccountMultiple, mdiPlus,
  mdiAccount,
  mdiFormatListText,
 mdiClipboardList,
  mdiCashRegister,
  mdiElevator,
  mdiElevatorUp,
  mdiElevatorPassenger,
  mdiElevatorPassengerOffOutline,
  mdiElevatorPassengerOutline,
  mdiEmoticonSick,
  mdiPrinterPosWrenchOutline,
  mdiListBoxOutline
} from "@mdi/js";

export default [
  {
    to: "/",
    icon: mdiMonitor,
    label: "Dashboard",
  },
  {

    label: "Elevators",
    icon: mdiElevatorPassengerOutline,
    menu: [
      {
        label: "View All",
        icon: mdiViewList,
        to: "/elevators",
      },
      {
        label: "Add New",
        icon: mdiPlus,
        to: "/add-elevator",
      },
    ],
  },
  {

    label: "Regulations",
    icon: mdiPrinterPosWrenchOutline,
    menu: [
      {
        label: "Sanitary",
        icon: mdiEmoticonSick,
        to: "/sanitary_issues",
      },
      {
        label: "Technical",
        icon:  mdiPrinterPosWrenchOutline ,
        to: "/technical_issues",
      },
    ],
  },
  {
    to: "/attandance",
    icon: mdiListBoxOutline,
    label: "Attendance",
  },

  {

    label: "Users",
    icon: mdiAccountMultiple,
    menu: [
      {
        label: "Employees",
        icon: mdiAccount,
        to: "/employees",
      },
      {
        label: "Admins",
        icon: mdiLock,
        to: "/admins",
      },
    ],
  },



];
