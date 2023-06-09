import {

  mdiMonitor,
  mdiLock,
  mdiViewList,
  mdiHome, mdiAccountMultiple, mdiPlus,
  mdiAccount,
  mdiOrderBoolAscendingVariant,
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
      // Add a spacer object before the "Dashboard" item
      spacer: true,
    },
    {
      // Add a spacer object before the "Dashboard" item
      spacer: true,
    },
    {
      // Add a spacer object before the "Dashboard" item
      spacer: true,
    },
  {
    to: "/dashboard",
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

    label: "assignments",
    icon: mdiOrderBoolAscendingVariant,
    menu: [
      {
        label: "View All",
        icon: mdiViewList,
        to: "/assignments",
      },
      {
        label: "Add New",
        icon: mdiPlus,
        to: "/add-assignment",
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
        to: "/sanitary-issues",
      },
      {
        label: "Technical",
        icon:  mdiPrinterPosWrenchOutline ,
        to: "/technical-issues",
      },
    ],
  },
  {
    to: "/attendances",
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
        menu: [
          {
            label: "View All",
            icon: mdiViewList,
            to: "/employees",
          },
          {
            label: "Add New",
            icon: mdiPlus,
            to: "/add-employee",
          },
        ],
      },
      {
        label: "Admins",
        icon: mdiLock,
        menu: [
          {
            label: "View All",
            icon: mdiViewList,
            to: "/admins",
          },
          {
            label: "Add New",
            icon: mdiPlus,
            to: "/add-admin",
          },
        ],
      },
    ],
  },

 

];
