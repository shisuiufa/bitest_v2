export enum Role {
    Admin = "admin",
    Moderator = "moderator",
    User = "user",
}

export enum RoleValue {
    Admin = 1,
    Moderator = 2,
    User = 3,
}

export interface User {
    id?: string | number
    firstName: string
    lastName: string
    middleName: string
    email?: string
    emailVerifiedAt?: Date | string | null
    role: Role
    avatar?: string
}

export interface RegisterUser {
    id?: string | number
    firstName: string
    lastName: string
    middleName: string
    email: string
    role?: Role
    password: string
    password_confirmation: string
}
