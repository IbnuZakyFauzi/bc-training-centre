--
-- PostgreSQL database dump
--

\restrict hSq3k7y95UtpYcbeSajraiqN9GkULul4DQwVQ73vcBYarvCtaezyT1sMGgTpz7C

-- Dumped from database version 18.1
-- Dumped by pg_dump version 18.1

-- Started on 2026-09-22 15:35:11

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 237 (class 1259 OID 17171)
-- Name: cache; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration bigint NOT NULL
);


ALTER TABLE public.cache OWNER TO postgres;

--
-- TOC entry 238 (class 1259 OID 17182)
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration bigint NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO postgres;

--
-- TOC entry 236 (class 1259 OID 17137)
-- Name: competency_evaluations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.competency_evaluations (
    id bigint NOT NULL,
    ojt_logbook_id bigint NOT NULL,
    trainer_id bigint NOT NULL,
    overall_score smallint,
    competency_status character varying(255),
    assessment_payload json,
    trainer_comment text,
    revision_instruction text,
    evaluated_at timestamp(0) without time zone,
    sent_to_pjo_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    trainer_signature_path character varying(255),
    CONSTRAINT competency_evaluations_competency_status_check CHECK (((competency_status)::text = ANY ((ARRAY['competent'::character varying, 'not_yet_competent'::character varying])::text[])))
);


ALTER TABLE public.competency_evaluations OWNER TO postgres;

--
-- TOC entry 235 (class 1259 OID 17136)
-- Name: competency_evaluations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.competency_evaluations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.competency_evaluations_id_seq OWNER TO postgres;

--
-- TOC entry 5248 (class 0 OID 0)
-- Dependencies: 235
-- Name: competency_evaluations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.competency_evaluations_id_seq OWNED BY public.competency_evaluations.id;


--
-- TOC entry 222 (class 1259 OID 16951)
-- Name: equipment_categories; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.equipment_categories (
    id bigint NOT NULL,
    code character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.equipment_categories OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 16950)
-- Name: equipment_categories_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.equipment_categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.equipment_categories_id_seq OWNER TO postgres;

--
-- TOC entry 5249 (class 0 OID 0)
-- Dependencies: 221
-- Name: equipment_categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.equipment_categories_id_seq OWNED BY public.equipment_categories.id;


--
-- TOC entry 224 (class 1259 OID 16965)
-- Name: equipments; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.equipments (
    id bigint NOT NULL,
    equipment_category_id bigint NOT NULL,
    unit_code character varying(255) NOT NULL,
    model_name character varying(255) NOT NULL,
    serial_number character varying(255),
    status character varying(255) DEFAULT 'active'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.equipments OWNER TO postgres;

--
-- TOC entry 223 (class 1259 OID 16964)
-- Name: equipments_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.equipments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.equipments_id_seq OWNER TO postgres;

--
-- TOC entry 5250 (class 0 OID 0)
-- Dependencies: 223
-- Name: equipments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.equipments_id_seq OWNED BY public.equipments.id;


--
-- TOC entry 249 (class 1259 OID 17407)
-- Name: legacy_sqlite_archives; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.legacy_sqlite_archives (
    id bigint NOT NULL,
    source_table character varying(255) NOT NULL,
    source_id character varying(255),
    source_column character varying(255),
    payload json NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.legacy_sqlite_archives OWNER TO postgres;

--
-- TOC entry 248 (class 1259 OID 17406)
-- Name: legacy_sqlite_archives_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.legacy_sqlite_archives_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.legacy_sqlite_archives_id_seq OWNER TO postgres;

--
-- TOC entry 5251 (class 0 OID 0)
-- Dependencies: 248
-- Name: legacy_sqlite_archives_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.legacy_sqlite_archives_id_seq OWNED BY public.legacy_sqlite_archives.id;


--
-- TOC entry 242 (class 1259 OID 17241)
-- Name: logbook_assignments; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.logbook_assignments (
    id bigint NOT NULL,
    ojt_logbook_id bigint NOT NULL,
    user_id bigint NOT NULL,
    role_type character varying(255) NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    notes text,
    reviewed_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT logbook_assignments_role_type_check CHECK (((role_type)::text = ANY ((ARRAY['pengawas'::character varying, 'operator_pendamping'::character varying, 'instruktur'::character varying])::text[]))),
    CONSTRAINT logbook_assignments_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'approved'::character varying, 'rejected'::character varying])::text[])))
);


ALTER TABLE public.logbook_assignments OWNER TO postgres;

--
-- TOC entry 241 (class 1259 OID 17240)
-- Name: logbook_assignments_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.logbook_assignments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.logbook_assignments_id_seq OWNER TO postgres;

--
-- TOC entry 5252 (class 0 OID 0)
-- Dependencies: 241
-- Name: logbook_assignments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.logbook_assignments_id_seq OWNED BY public.logbook_assignments.id;


--
-- TOC entry 232 (class 1259 OID 17093)
-- Name: logbook_evidences; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.logbook_evidences (
    id bigint NOT NULL,
    ojt_logbook_id bigint NOT NULL,
    file_path character varying(255) NOT NULL,
    file_name character varying(255) NOT NULL,
    file_type character varying(255) DEFAULT 'image'::character varying NOT NULL,
    file_size character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT logbook_evidences_file_type_check CHECK (((file_type)::text = ANY ((ARRAY['image'::character varying, 'video'::character varying, 'document'::character varying])::text[])))
);


ALTER TABLE public.logbook_evidences OWNER TO postgres;

--
-- TOC entry 231 (class 1259 OID 17092)
-- Name: logbook_evidences_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.logbook_evidences_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.logbook_evidences_id_seq OWNER TO postgres;

--
-- TOC entry 5253 (class 0 OID 0)
-- Dependencies: 231
-- Name: logbook_evidences_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.logbook_evidences_id_seq OWNED BY public.logbook_evidences.id;


--
-- TOC entry 234 (class 1259 OID 17114)
-- Name: logbook_histories; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.logbook_histories (
    id bigint NOT NULL,
    ojt_logbook_id bigint NOT NULL,
    user_id bigint,
    action character varying(255) NOT NULL,
    from_status character varying(255),
    to_status character varying(255) NOT NULL,
    comment text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.logbook_histories OWNER TO postgres;

--
-- TOC entry 233 (class 1259 OID 17113)
-- Name: logbook_histories_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.logbook_histories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.logbook_histories_id_seq OWNER TO postgres;

--
-- TOC entry 5254 (class 0 OID 0)
-- Dependencies: 233
-- Name: logbook_histories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.logbook_histories_id_seq OWNED BY public.logbook_histories.id;


--
-- TOC entry 220 (class 1259 OID 16927)
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 16926)
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO postgres;

--
-- TOC entry 5255 (class 0 OID 0)
-- Dependencies: 219
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- TOC entry 247 (class 1259 OID 17362)
-- Name: notifications; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.notifications (
    id uuid NOT NULL,
    type character varying(255) NOT NULL,
    notifiable_type character varying(255) NOT NULL,
    notifiable_id bigint NOT NULL,
    data text NOT NULL,
    read_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.notifications OWNER TO postgres;

--
-- TOC entry 244 (class 1259 OID 17276)
-- Name: ojt_final_evaluations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ojt_final_evaluations (
    id bigint NOT NULL,
    ojt_logbook_id bigint,
    trainer_id bigint NOT NULL,
    nama_operator character varying(255) NOT NULL,
    perusahaan character varying(255) NOT NULL,
    lokasi_kerja character varying(255) NOT NULL,
    jenis_unit_a2b character varying(255) NOT NULL,
    jenis_sertifikasi character varying(255) NOT NULL,
    instruktur character varying(255) NOT NULL,
    operator_pendamping character varying(255) NOT NULL,
    tanggal_penilaian date NOT NULL,
    tahap_penilaian character varying(255) NOT NULL,
    sub_tahap character varying(255),
    p2h_status character varying(255) NOT NULL,
    teknik_pengoperasian_status character varying(255) NOT NULL,
    kepatuhan_status character varying(255) NOT NULL,
    kedisiplinan_status character varying(255) NOT NULL,
    kesimpulan character varying(255) NOT NULL,
    catatan text,
    instruktur_signature_path character varying(255),
    pengawas_signature_path character varying(255),
    operator_pendamping_signature_path character varying(255),
    kabag_signature_path character varying(255),
    penanggung_jawab_signature_path character varying(255),
    hse_signature_path character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    phase character varying(255),
    status character varying(255) DEFAULT 'submitted'::character varying NOT NULL,
    tc_approved_by bigint,
    tc_approved_at timestamp(0) without time zone,
    tc_notes text,
    pjo_approved_by bigint,
    pjo_approved_at timestamp(0) without time zone,
    pjo_notes text,
    hse_approved_by bigint,
    hse_approved_at timestamp(0) without time zone,
    hse_notes text,
    completed_at timestamp(0) without time zone,
    sub_tahap_keterangan character varying(255),
    CONSTRAINT ojt_final_evaluations_jenis_sertifikasi_check CHECK (((jenis_sertifikasi)::text = ANY ((ARRAY['Green'::character varying, 'Skill-up'::character varying, 'Experience'::character varying, 'Experience_internal'::character varying, 'Experience_external'::character varying])::text[]))),
    CONSTRAINT ojt_final_evaluations_kedisiplinan_status_check CHECK (((kedisiplinan_status)::text = ANY ((ARRAY['K'::character varying, 'BK'::character varying])::text[]))),
    CONSTRAINT ojt_final_evaluations_kepatuhan_status_check CHECK (((kepatuhan_status)::text = ANY ((ARRAY['K'::character varying, 'BK'::character varying])::text[]))),
    CONSTRAINT ojt_final_evaluations_kesimpulan_check CHECK (((kesimpulan)::text = ANY ((ARRAY['kompeten'::character varying, 'belum_kompeten'::character varying])::text[]))),
    CONSTRAINT ojt_final_evaluations_p2h_status_check CHECK (((p2h_status)::text = ANY ((ARRAY['K'::character varying, 'BK'::character varying])::text[]))),
    CONSTRAINT ojt_final_evaluations_tahap_penilaian_check CHECK (((tahap_penilaian)::text = ANY ((ARRAY['pendampingan'::character varying, 'tanpa_pendampingan'::character varying])::text[]))),
    CONSTRAINT ojt_final_evaluations_teknik_pengoperasian_status_check CHECK (((teknik_pengoperasian_status)::text = ANY ((ARRAY['K'::character varying, 'BK'::character varying])::text[])))
);


ALTER TABLE public.ojt_final_evaluations OWNER TO postgres;

--
-- TOC entry 243 (class 1259 OID 17275)
-- Name: ojt_final_evaluations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ojt_final_evaluations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ojt_final_evaluations_id_seq OWNER TO postgres;

--
-- TOC entry 5256 (class 0 OID 0)
-- Dependencies: 243
-- Name: ojt_final_evaluations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ojt_final_evaluations_id_seq OWNED BY public.ojt_final_evaluations.id;


--
-- TOC entry 230 (class 1259 OID 17034)
-- Name: ojt_logbooks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ojt_logbooks (
    id bigint NOT NULL,
    logbook_number character varying(255) NOT NULL,
    trainee_id bigint NOT NULL,
    trainer_id bigint,
    equipment_category_id bigint,
    equipment_id bigint,
    date date,
    shift character varying(255) DEFAULT 'day'::character varying,
    location character varying(255),
    start_time time(0) without time zone,
    finish_time time(0) without time zone,
    hm_start numeric(8,1) DEFAULT '0'::numeric,
    hm_end numeric(8,1) DEFAULT '0'::numeric,
    total_hm numeric(8,1) DEFAULT '0'::numeric NOT NULL,
    status character varying(255) DEFAULT 'draft'::character varying NOT NULL,
    revision_notes text,
    submitted_at timestamp(0) without time zone,
    verified_at timestamp(0) without time zone,
    approved_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    sop_payload text,
    pjo_id bigint,
    pjo_notes text,
    pjo_decided_at timestamp(0) without time zone,
    training_centre_id bigint,
    training_centre_notes text,
    training_centre_decided_at timestamp(0) without time zone,
    trainer_signature_path character varying(255),
    pjo_signature_path character varying(255),
    training_centre_signature_path character varying(255),
    equipment_number character varying(255),
    assigned_pjo_id bigint,
    assigned_tc_id bigint,
    selected_pengawas_ids json,
    selected_operator_pendamping_ids json,
    hm_day numeric(8,1),
    hm_night numeric(8,1),
    CONSTRAINT ojt_logbooks_shift_check CHECK (((shift)::text = ANY ((ARRAY['day'::character varying, 'night'::character varying])::text[]))),
    CONSTRAINT ojt_logbooks_status_check CHECK (((status)::text = ANY ((ARRAY['draft'::character varying, 'submitted'::character varying, 'revision'::character varying, 'verified'::character varying, 'final_approved'::character varying])::text[])))
);


ALTER TABLE public.ojt_logbooks OWNER TO postgres;

--
-- TOC entry 229 (class 1259 OID 17033)
-- Name: ojt_logbooks_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ojt_logbooks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ojt_logbooks_id_seq OWNER TO postgres;

--
-- TOC entry 5257 (class 0 OID 0)
-- Dependencies: 229
-- Name: ojt_logbooks_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ojt_logbooks_id_seq OWNED BY public.ojt_logbooks.id;


--
-- TOC entry 227 (class 1259 OID 17012)
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO postgres;

--
-- TOC entry 228 (class 1259 OID 17021)
-- Name: sessions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO postgres;

--
-- TOC entry 246 (class 1259 OID 17337)
-- Name: trainee_phase_histories; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.trainee_phase_histories (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    from_phase character varying(255),
    to_phase character varying(255),
    evaluation_id bigint,
    approved_by bigint,
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.trainee_phase_histories OWNER TO postgres;

--
-- TOC entry 245 (class 1259 OID 17336)
-- Name: trainee_phase_histories_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.trainee_phase_histories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.trainee_phase_histories_id_seq OWNER TO postgres;

--
-- TOC entry 5258 (class 0 OID 0)
-- Dependencies: 245
-- Name: trainee_phase_histories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.trainee_phase_histories_id_seq OWNED BY public.trainee_phase_histories.id;


--
-- TOC entry 240 (class 1259 OID 17217)
-- Name: trainee_trainer; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.trainee_trainer (
    id bigint NOT NULL,
    trainee_id bigint NOT NULL,
    trainer_id bigint NOT NULL,
    trainer_type character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT trainee_trainer_trainer_type_check CHECK (((trainer_type)::text = ANY ((ARRAY['instruktur'::character varying, 'pengawas'::character varying, 'operator_pendamping'::character varying])::text[])))
);


ALTER TABLE public.trainee_trainer OWNER TO postgres;

--
-- TOC entry 239 (class 1259 OID 17216)
-- Name: trainee_trainer_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.trainee_trainer_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.trainee_trainer_id_seq OWNER TO postgres;

--
-- TOC entry 5259 (class 0 OID 0)
-- Dependencies: 239
-- Name: trainee_trainer_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.trainee_trainer_id_seq OWNED BY public.trainee_trainer.id;


--
-- TOC entry 226 (class 1259 OID 16987)
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    sid character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255),
    password character varying(255) NOT NULL,
    phone character varying(255),
    avatar character varying(255),
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    signature_path character varying(255),
    must_change_password boolean DEFAULT false NOT NULL,
    is_super_admin boolean DEFAULT false NOT NULL,
    trainer_type character varying(255),
    certification character varying(255),
    company character varying(255),
    equipment_category_id bigint,
    equipment_number character varying(255),
    current_phase character varying(255),
    role character varying(255) DEFAULT 'trainee'::character varying CONSTRAINT users_role_new_not_null1 NOT NULL,
    initial_hm_day numeric(10,1) DEFAULT '0'::numeric NOT NULL,
    initial_hm_night numeric(10,1) DEFAULT '0'::numeric NOT NULL,
    CONSTRAINT users_certification_check CHECK (((certification)::text = ANY ((ARRAY['Green'::character varying, 'Skill-up'::character varying, 'Experience'::character varying, 'Experience_internal'::character varying])::text[]))),
    CONSTRAINT users_role_new_check1 CHECK (((role)::text = ANY ((ARRAY['trainee'::character varying, 'trainer'::character varying, 'admin'::character varying, 'pjo'::character varying, 'hse_ct'::character varying])::text[]))),
    CONSTRAINT users_trainer_type_check CHECK (((trainer_type)::text = ANY ((ARRAY['instruktur'::character varying, 'pengawas'::character varying, 'operator_pendamping'::character varying])::text[])))
);


ALTER TABLE public.users OWNER TO postgres;

--
-- TOC entry 225 (class 1259 OID 16986)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO postgres;

--
-- TOC entry 5260 (class 0 OID 0)
-- Dependencies: 225
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- TOC entry 4955 (class 2604 OID 17140)
-- Name: competency_evaluations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.competency_evaluations ALTER COLUMN id SET DEFAULT nextval('public.competency_evaluations_id_seq'::regclass);


--
-- TOC entry 4937 (class 2604 OID 16954)
-- Name: equipment_categories id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.equipment_categories ALTER COLUMN id SET DEFAULT nextval('public.equipment_categories_id_seq'::regclass);


--
-- TOC entry 4938 (class 2604 OID 16968)
-- Name: equipments id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.equipments ALTER COLUMN id SET DEFAULT nextval('public.equipments_id_seq'::regclass);


--
-- TOC entry 4962 (class 2604 OID 17410)
-- Name: legacy_sqlite_archives id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.legacy_sqlite_archives ALTER COLUMN id SET DEFAULT nextval('public.legacy_sqlite_archives_id_seq'::regclass);


--
-- TOC entry 4957 (class 2604 OID 17244)
-- Name: logbook_assignments id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.logbook_assignments ALTER COLUMN id SET DEFAULT nextval('public.logbook_assignments_id_seq'::regclass);


--
-- TOC entry 4952 (class 2604 OID 17096)
-- Name: logbook_evidences id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.logbook_evidences ALTER COLUMN id SET DEFAULT nextval('public.logbook_evidences_id_seq'::regclass);


--
-- TOC entry 4954 (class 2604 OID 17117)
-- Name: logbook_histories id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.logbook_histories ALTER COLUMN id SET DEFAULT nextval('public.logbook_histories_id_seq'::regclass);


--
-- TOC entry 4936 (class 2604 OID 16930)
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- TOC entry 4959 (class 2604 OID 17279)
-- Name: ojt_final_evaluations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ojt_final_evaluations ALTER COLUMN id SET DEFAULT nextval('public.ojt_final_evaluations_id_seq'::regclass);


--
-- TOC entry 4946 (class 2604 OID 17037)
-- Name: ojt_logbooks id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ojt_logbooks ALTER COLUMN id SET DEFAULT nextval('public.ojt_logbooks_id_seq'::regclass);


--
-- TOC entry 4961 (class 2604 OID 17340)
-- Name: trainee_phase_histories id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.trainee_phase_histories ALTER COLUMN id SET DEFAULT nextval('public.trainee_phase_histories_id_seq'::regclass);


--
-- TOC entry 4956 (class 2604 OID 17220)
-- Name: trainee_trainer id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.trainee_trainer ALTER COLUMN id SET DEFAULT nextval('public.trainee_trainer_id_seq'::regclass);


--
-- TOC entry 4940 (class 2604 OID 16990)
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- TOC entry 5230 (class 0 OID 17171)
-- Dependencies: 237
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache (key, value, expiration) FROM stdin;
ojt_evaluation_competency_monitoring_system_cache_11111|127.0.0.1:timer	i:1789007138;	1789007138
ojt_evaluation_competency_monitoring_system_cache_11111|127.0.0.1	i:2;	1789007138
\.


--
-- TOC entry 5231 (class 0 OID 17182)
-- Dependencies: 238
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- TOC entry 5229 (class 0 OID 17137)
-- Dependencies: 236
-- Data for Name: competency_evaluations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.competency_evaluations (id, ojt_logbook_id, trainer_id, overall_score, competency_status, assessment_payload, trainer_comment, revision_instruction, evaluated_at, sent_to_pjo_at, created_at, updated_at, trainer_signature_path) FROM stdin;
\.


--
-- TOC entry 5215 (class 0 OID 16951)
-- Dependencies: 222
-- Data for Name: equipment_categories; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.equipment_categories (id, code, name, description, created_at, updated_at) FROM stdin;
1	EXC	Heavy Excavator	Hydraulic Excavator Loading Units	2026-09-10 10:22:32	2026-09-10 10:22:32
2	HT	Haul Truck	Off-Highway Dump Trucks	2026-09-10 10:22:32	2026-09-10 10:22:32
3	DZ	Track Dozer	Crawler Bulldozers	2026-09-10 10:22:32	2026-09-10 10:22:32
4	MG	Motor Grader	Haul Road Maintenance Graders	2026-09-10 10:22:32	2026-09-10 10:22:32
5	HDT	Heavy Dump Truck	Off-Highway Heavy Dump Trucks	2026-09-10 10:44:27	2026-09-10 10:44:27
6	LDT	Light Dump Truck	Off-Highway Light Dump Trucks	2026-09-10 10:44:27	2026-09-10 10:44:27
7	SDT	Semi Dump Trailler	Semi Dump Trailler Units	2026-09-10 10:44:27	2026-09-10 10:44:27
8	ADT	Articulated Dump Truck	Articulated Dump Truck Units	2026-09-10 10:44:27	2026-09-10 10:44:27
9	WL	Wheel Loader	Wheel Loading Units	2026-09-10 10:44:27	2026-09-10 10:44:27
\.


--
-- TOC entry 5217 (class 0 OID 16965)
-- Dependencies: 224
-- Data for Name: equipments; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.equipments (id, equipment_category_id, unit_code, model_name, serial_number, status, created_at, updated_at) FROM stdin;
1	1	EX-2001	Komatsu PC2000-8	KMTPC2000-202401	active	2026-09-10 10:22:32	2026-09-10 10:22:32
2	2	HT-7042	Caterpillar 777F	CAT0777F-994812	active	2026-09-10 10:22:32	2026-09-10 10:22:32
3	3	DZ-3015	Caterpillar D10T2	CAT0D10T-102934	active	2026-09-10 10:22:32	2026-09-10 10:22:32
4	4	MG-1002	Komatsu GD825A-2	KMTGD825-554129	active	2026-09-10 10:22:32	2026-09-10 10:22:32
\.


--
-- TOC entry 5242 (class 0 OID 17407)
-- Dependencies: 249
-- Data for Name: legacy_sqlite_archives; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.legacy_sqlite_archives (id, source_table, source_id, source_column, payload, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 5235 (class 0 OID 17241)
-- Dependencies: 242
-- Data for Name: logbook_assignments; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.logbook_assignments (id, ojt_logbook_id, user_id, role_type, status, notes, reviewed_at, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 5225 (class 0 OID 17093)
-- Dependencies: 232
-- Data for Name: logbook_evidences; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.logbook_evidences (id, ojt_logbook_id, file_path, file_name, file_type, file_size, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 5227 (class 0 OID 17114)
-- Dependencies: 234
-- Data for Name: logbook_histories; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.logbook_histories (id, ojt_logbook_id, user_id, action, from_status, to_status, comment, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 5213 (class 0 OID 16927)
-- Dependencies: 220
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	2026_01_01_000001_create_departments_table	1
2	2026_01_01_000002_create_equipment_categories_table	1
3	2026_01_01_000003_create_equipments_table	1
4	2026_01_01_000004_create_users_table	1
5	2026_01_01_000005_create_ojt_logbooks_table	1
6	2026_01_01_000006_create_logbook_evidences_table	1
7	2026_01_01_000007_create_logbook_histories_table	1
8	2026_07_31_000008_add_sop_payload_to_ojt_logbooks_table	1
9	2026_08_03_000009_create_competency_evaluations_table	1
10	2026_08_03_000010_add_trainer_signature_to_competency_evaluations_table	1
11	2026_08_04_000011_add_department_operation_approval_to_ojt_logbooks_table	1
12	2026_08_04_000012_add_training_centre_approval_to_ojt_logbooks_table	1
13	2026_08_04_000013_split_supervisor_and_final_approval_statuses_on_ojt_logbooks_table	1
14	2026_08_06_000014_add_signature_path_to_users_table	1
15	2026_08_06_000015_add_approval_signature_paths_to_ojt_logbooks_table	1
16	2026_08_06_113538_create_cache_table	1
17	2026_08_06_120000_add_equipment_number_to_ojt_logbooks_table	1
18	2026_08_07_151343_make_ojt_logbook_draft_fields_nullable	1
19	2026_08_07_152635_make_hm_fields_nullable_on_ojt_logbooks	1
20	2026_08_10_000001_add_must_change_password_to_users_table	1
21	2026_08_10_000002_add_is_super_admin_to_users_table	1
22	2026_08_10_000003_add_assignment_columns_to_ojt_logbooks_table	1
23	2026_08_11_000001_add_trainer_type_and_remove_department_ops_role_from_users_table	1
24	2026_08_12_000001_create_trainee_trainer_table	1
25	2026_08_12_000002_rename_nrp_to_sid_in_users_table	1
26	2026_08_13_000001_add_daily_assignment_columns_to_ojt_logbooks_table	1
27	2026_08_13_000002_create_logbook_assignments_table	1
28	2026_08_14_104719_add_trainee_profile_fields_to_users_table	1
29	2026_08_14_141443_add_trainer_ratings_to_ojt_logbooks_table	1
30	2026_08_17_153344_add_hm_day_night_to_ojt_logbooks_table	1
31	2026_08_19_000001_create_ojt_final_evaluations_table	1
32	2026_08_19_131219_make_ojt_logbook_id_nullable_on_ojt_final_evaluations_table	1
33	2026_08_20_000001_add_current_phase_to_users_table	1
34	2026_08_20_000002_normalize_certification_and_init_phase	1
35	2026_08_20_000003_add_phase_and_approval_to_ojt_final_evaluations_table	1
36	2026_08_20_000004_create_trainee_phase_histories_table	1
37	2026_08_20_030000_create_notifications_table	1
38	2026_08_24_135041_add_sub_tahap_keterangan_to_ojt_final_evaluations_table	1
39	2026_08_27_000001_remove_department_id_from_users_and_ojt_logbooks_table	1
40	2026_08_27_000002_remove_supervisor_and_daily_activity_from_ojt_logbooks_and_users_table	1
41	2026_08_27_000003_create_trainer_rating_summaries_table	1
42	2026_08_31_000001_make_email_nullable_on_users_table	1
43	2026_09_02_141207_add_initial_hm_to_users_table	1
44	2026_09_06_141510_remove_trainer_ratings_from_ojt_logbooks_table	1
45	2026_09_06_141511_drop_trainer_rating_summaries_table	1
46	2026_09_06_141512_create_legacy_sqlite_archives_table	1
47	2026_09_06_141513_allow_experience_internal_certification	1
48	2026_09_07_145246_allow_experience_certification_on_ojt_final_evaluations_table	1
49	2026_09_10_110009_drop_sticker_expired_at_from_users_table	2
\.


--
-- TOC entry 5240 (class 0 OID 17362)
-- Dependencies: 247
-- Data for Name: notifications; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.notifications (id, type, notifiable_type, notifiable_id, data, read_at, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 5237 (class 0 OID 17276)
-- Dependencies: 244
-- Data for Name: ojt_final_evaluations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ojt_final_evaluations (id, ojt_logbook_id, trainer_id, nama_operator, perusahaan, lokasi_kerja, jenis_unit_a2b, jenis_sertifikasi, instruktur, operator_pendamping, tanggal_penilaian, tahap_penilaian, sub_tahap, p2h_status, teknik_pengoperasian_status, kepatuhan_status, kedisiplinan_status, kesimpulan, catatan, instruktur_signature_path, pengawas_signature_path, operator_pendamping_signature_path, kabag_signature_path, penanggung_jawab_signature_path, hse_signature_path, created_at, updated_at, phase, status, tc_approved_by, tc_approved_at, tc_notes, pjo_approved_by, pjo_approved_at, pjo_notes, hse_approved_by, hse_approved_at, hse_notes, completed_at, sub_tahap_keterangan) FROM stdin;
\.


--
-- TOC entry 5223 (class 0 OID 17034)
-- Dependencies: 230
-- Data for Name: ojt_logbooks; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ojt_logbooks (id, logbook_number, trainee_id, trainer_id, equipment_category_id, equipment_id, date, shift, location, start_time, finish_time, hm_start, hm_end, total_hm, status, revision_notes, submitted_at, verified_at, approved_at, created_at, updated_at, sop_payload, pjo_id, pjo_notes, pjo_decided_at, training_centre_id, training_centre_notes, training_centre_decided_at, trainer_signature_path, pjo_signature_path, training_centre_signature_path, equipment_number, assigned_pjo_id, assigned_tc_id, selected_pengawas_ids, selected_operator_pendamping_ids, hm_day, hm_night) FROM stdin;
16	LOG-202608-0010	6	6	\N	\N	2026-08-28	day	BMO 1	\N	\N	1230.0	1240.0	10.0	final_approved	\N	\N	\N	\N	2026-09-10 10:39:00	2026-09-10 10:39:00	{"meta":{"unit_type":"DZ"},"track":{"groups":[{"items":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]},{"items":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":null,"trainee_feedback":"N\\/A"},{"status":4,"trainee_feedback":null}]},{"items":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]},{"items":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]}],"compliance":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}],"behavior":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]},"excavator":{"groups":[{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]}],"compliance":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}],"behavior":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},"dumptruck":{"groups":[{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]}],"compliance":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}],"behavior":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},"semidump":{"groups":[{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]}],"compliance":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}],"behavior":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},"wheelloader":{"groups":[{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]}]}}	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
17	LOG-202608-0011	6	6	\N	\N	2026-08-31	day	BMO 1	\N	\N	1340.0	1386.0	46.0	final_approved	\N	\N	\N	\N	2026-09-10 10:39:00	2026-09-10 10:39:00	{"meta":{"unit_type":"DZ"},"track":{"groups":[{"items":[{"status":4,"trainee_feedback":null},{"status":2,"trainee_feedback":"Penggunaan Tilt Blade masih kasar"},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]},{"items":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":null,"trainee_feedback":"N\\/A"},{"status":4,"trainee_feedback":null}]},{"items":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]},{"items":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]}],"compliance":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}],"behavior":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]},"excavator":{"groups":[{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]}],"compliance":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}],"behavior":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},"dumptruck":{"groups":[{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]}],"compliance":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}],"behavior":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},"semidump":{"groups":[{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]}],"compliance":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}],"behavior":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},"wheelloader":{"groups":[{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]}]}}	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
18	LOG-202608-0012	6	6	\N	\N	2026-08-31	night	BMO 1	\N	\N	1386.0	1394.0	8.0	final_approved	\N	\N	\N	\N	2026-09-10 10:39:00	2026-09-10 10:39:00	{"meta":{"unit_type":"DZ"},"track":{"groups":[{"items":[{"status":4,"trainee_feedback":null},{"status":2,"trainee_feedback":"Masih kasar"},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]},{"items":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]},{"items":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]},{"items":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]}],"compliance":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}],"behavior":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]},"excavator":{"groups":[{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]}],"compliance":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}],"behavior":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},"dumptruck":{"groups":[{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]}],"compliance":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}],"behavior":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},"semidump":{"groups":[{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]}],"compliance":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}],"behavior":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},"wheelloader":{"groups":[{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]}]}}	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
19	LOG-202608-0013	6	6	\N	\N	2026-08-31	night	BMO 1	\N	\N	1394.0	1402.0	8.0	final_approved	\N	\N	\N	\N	2026-09-10 10:39:00	2026-09-10 10:39:00	{"meta":{"category_code":"DZ","unit_family":"track","trainee_name":"Henky Udiansyah","certification":"Green","company":"PT Maju Mundur","sticker_expired_at":"2026-12-31","assessment_mode":"pendampingan","assessment_stage_detail":null,"unit_type":"DZ"},"track":{"groups":[{"title":"Dozing & Digging untuk Unit (DZ) \\/ Grading & Digging untuk Unit (GR)","subtitle":"Cara memosisisikan blade, menggali, dan mendorong material","items":[{"code":"1.1","label":"Cara memposisikan Blade pada saat mendorong\\/ grading","kind":"Skl","status":4,"trainee_feedback":null},{"code":"1.2","label":"Penggunaan Tilt Blade","kind":"Knw","status":4,"trainee_feedback":null},{"code":"1.3","label":"Cara Pengoperasian blade untuk mendorong\\/ ditching","kind":"Skl","status":4,"trainee_feedback":null},{"code":"1.4","label":"Cara Pengoperasian blade untuk menggali\\/sloping","kind":"Skl","status":4,"trainee_feedback":null},{"code":"1.5","label":"Penyesuaian beban dengan rpm\\/posisi transmissi","kind":"Skl","status":4,"trainee_feedback":null},{"code":"1.6","label":"Teknik dozing\\/grading\\/digging","kind":"Skl","status":4,"trainee_feedback":null}]},{"title":"Spreading & Leveling","subtitle":"Pengoperasian untuk meratakan, memadatkan, dan membentuk area kerja","items":[{"code":"2.1","label":"Penggunaan Speed\\/ Transmissi saat bergerak","kind":"Knw","status":4,"trainee_feedback":null},{"code":"2.2","label":"Cara leveling menggunakan tilt","kind":"Knw","status":4,"trainee_feedback":null},{"code":"2.3","label":"Cara menghampar material untuk membuat jalan, menimbun lubang dll","kind":"Skl","status":4,"trainee_feedback":null},{"code":"2.4","label":"Filling pada saat melevelkan area kerja","kind":"Skl","status":4,"trainee_feedback":null},{"code":"2.5","label":"Penggunaan Steering","kind":"Skl","status":4,"trainee_feedback":null},{"code":"2.6","label":"Penggunaan Articulated (Khusus untuk unit GR)","kind":"Skl","trainee_feedback":"N\\/A","status":null},{"code":"2.7","label":"Teknik spreading\\/levelling","kind":"Skl","status":4,"trainee_feedback":null}]},{"title":"Ripping","subtitle":"Khusus untuk pekerjaan ripping dan pembukaan material keras","items":[{"code":"3.1","label":"Cara memposisikan Ripper","kind":"Skl","status":4,"trainee_feedback":null},{"code":"3.2","label":"Teknik Penetrasi Ripping","kind":"Knw","status":4,"trainee_feedback":null},{"code":"3.3","label":"Penyesuaian posisi ripper dengan kekerasan material","kind":"Skl","status":4,"trainee_feedback":null}]},{"title":"Finishing","subtitle":"Finishing grading dan koreksi permukaan kerja","items":[{"code":"4.1","label":"Kesesuaian penggunaan speed","kind":"Skl","status":4,"trainee_feedback":null},{"code":"4.2","label":"Hasil akhir pendorongan (hasil pekerjaan)","kind":"Skl","status":4,"trainee_feedback":null}]}],"compliance":[{"code":"1","label":"Kesehatan fisik dan perlengkapan\\/penggunaan APD","kind":"Knw","status":4,"trainee_feedback":null},{"code":"2","label":"Menaiki dan menuruni Unit (Three point contact)","kind":"Skl","status":4,"trainee_feedback":null},{"code":"3","label":"Penyetelan tempat duduk","kind":"Skl","status":4,"trainee_feedback":null},{"code":"4","label":"Penggunaan sabuk pengaman\\/ safety belt","kind":"Atd","status":4,"trainee_feedback":null},{"code":"5","label":"Penggunaan klakson dan lampu-lampu","kind":"Skl","status":4,"trainee_feedback":null},{"code":"6","label":"Keselamatan saat digging, dozing, spreading, levelling, ripping, travelling","kind":"Skl","status":4,"trainee_feedback":null},{"code":"7","label":"Penyesuaian jenis alat dengan lokasi pekerjaan","kind":"Skl","status":4,"trainee_feedback":null},{"code":"8","label":"Kepedulian terhadap patok-patok survey dan rambu","kind":"Atd","status":4,"trainee_feedback":null},{"code":"9","label":"Parkir unit ditempat yang rata dan aman (pasang lock dan cara meletakkan attachment)","kind":"Skl","status":4,"trainee_feedback":null},{"code":"10","label":"Keselamatan selama operasi","kind":"Knw","status":4,"trainee_feedback":null}],"behavior":[{"code":"1","label":"Mempedulikan pemakaian fuel\\/ bahan bakar","kind":"Atd","status":4,"trainee_feedback":null},{"code":"2","label":"Mempedulikan pemakaian tyre\\/ undercarriage","kind":"Atd","status":4,"trainee_feedback":null},{"code":"3","label":"Mempedulikan akan ketidaknormalan unit","kind":"Atd","status":4,"trainee_feedback":null},{"code":"4","label":"Mempedulikan untuk bekerja dengan efektif dan efisien","kind":"Atd","status":4,"trainee_feedback":null},{"code":"5","label":"Mempedulikan untuk meniadakan pemborosan dimanapun","kind":"Atd","status":4,"trainee_feedback":null},{"code":"6","label":"Melaksanakan aktivitas sesuai instruksi","kind":"Atd","status":4,"trainee_feedback":null},{"code":"7","label":"Berusaha untuk melakukan yang terbaik","kind":"Atd","status":4,"trainee_feedback":null},{"code":"8","label":"Selalu siap menerima tugas yang diberikan","kind":"Atd","status":4,"trainee_feedback":null},{"code":"9","label":"Berani mengingatkan jika ada yang berbuat kesalahan","kind":"Atd","status":4,"trainee_feedback":null},{"code":"10","label":"Disiplin waktu saat pelaksanaan pelatihan","kind":"Atd","status":4,"trainee_feedback":null},{"code":"11","label":"Mematuhi semua aturan yang berlaku","kind":"Atd","status":4,"trainee_feedback":null},{"code":"12","label":"Tidak pernah mangkir","kind":"Atd","status":4,"trainee_feedback":null},{"code":"13","label":"Melaksanakan tugas kelompok bersama-sama","kind":"Atd","status":4,"trainee_feedback":null},{"code":"14","label":"Berinisiatif untuk membantu","kind":"Atd","status":4,"trainee_feedback":null},{"code":"15","label":"Selalu antusias jika diberi tugas","kind":"Atd","status":4,"trainee_feedback":null},{"code":"16","label":"Melaporkan setiap kejadian diluar wewenangnya","kind":"Atd","status":4,"trainee_feedback":null},{"code":"17","label":"Tidak ragu-ragu jika diberi instruksi","kind":"Atd","status":4,"trainee_feedback":null},{"code":"18","label":"Mengoperasikan unit dengan penuh keyakinan","kind":"Atd","status":4,"trainee_feedback":null},{"code":"19","label":"Bersikap proaktif di setiap kegiatan","kind":"Atd","status":4,"trainee_feedback":null},{"code":"20","label":"Tidak malu untuk bertanya jika ada kesulitan","kind":"Atd","status":4,"trainee_feedback":null}]},"excavator":{"groups":[{"title":"Positioning","subtitle":"Cara memposisikan unit, track, dan upper structure di front loading","items":[{"code":"1.1","label":"Cara memposisikan unit di front loading","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.2","label":"Cara membuat landasan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.3","label":"Cara mengatur track dan upper structure","kind":"Skl","trainee_feedback":null,"status":null}]},{"title":"Loading & Dumping","subtitle":"Cara swing, memuat, dan dumping yang aman","items":[{"code":"2.1","label":"Cara swing muatan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.2","label":"Cara swing kosongan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.3","label":"Kombinasi gerakan","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.4","label":"Cara dumping dan kerapihan muatan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.5","label":"Sudut swing","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.6","label":"Cycle time","kind":"Knw","trainee_feedback":null,"status":null}]},{"title":"Digging","subtitle":"Teknik digging dan pengaturan kerja bucket","items":[{"code":"3.1","label":"Teknik digging (urutan pengambilan)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.2","label":"Sudut pengambilan (digging)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.3","label":"Gerakan kombinasi pada saat digging","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.4","label":"Volume bucket","kind":"Knw","trainee_feedback":null,"status":null}]},{"title":"Sloping","subtitle":"Teknik pembuatan slope dan kerapihan permukaan","items":[{"code":"4.1","label":"Teknik pembuatan slope","kind":"Skl","trainee_feedback":null,"status":null},{"code":"4.2","label":"Kerapihan slope","kind":"Skl","trainee_feedback":null,"status":null}]}],"compliance":[{"code":"1","label":"Kesehatan fisik dan perlengkapan\\/ penggunaan APD","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2","label":"Menaiki dan menuruni Unit (Three point contact)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3","label":"Penyetelan tempat duduk","kind":"Skl","trainee_feedback":null,"status":null},{"code":"4","label":"Penggunaan sabuk pengaman\\/safety belt","kind":"Atd","trainee_feedback":null,"status":null},{"code":"5","label":"Penggunaan klakson dan lampu-lampu","kind":"Skl","trainee_feedback":null,"status":null},{"code":"6","label":"Keselamatan saat loading, unloading, positioning, traveling, dan digging","kind":"Skl","trainee_feedback":null,"status":null},{"code":"7","label":"Penyesuaian jenis alat dengan lokasi pekerjaan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"8","label":"Kepedulian terhadap patok-patok survey dan rambu","kind":"Atd","trainee_feedback":null,"status":null},{"code":"9","label":"Parkir unit ditempat yang rata dan aman (pasang lock dan cara meletakkan attachment)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"10","label":"Keselamatan selama operasi","kind":"Knw","trainee_feedback":null,"status":null}],"behavior":[{"code":"1","label":"Mempedulikan pemakaian fuel\\/ bahan bakar","kind":"Atd","trainee_feedback":null,"status":null},{"code":"2","label":"Mempedulikan pemakaian tyre\\/ undercarriage","kind":"Atd","trainee_feedback":null,"status":null},{"code":"3","label":"Mempedulikan akan ketidaknormalan unit","kind":"Atd","trainee_feedback":null,"status":null},{"code":"4","label":"Mempedulikan untuk bekerja dengan efektif dan efisien","kind":"Atd","trainee_feedback":null,"status":null},{"code":"5","label":"Mempedulikan untuk meniadakan pemborosan dimanapun","kind":"Atd","trainee_feedback":null,"status":null},{"code":"6","label":"Melaksanakan aktivitas sesuai instruksi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"7","label":"Berusaha untuk melakukan yang terbaik","kind":"Atd","trainee_feedback":null,"status":null},{"code":"8","label":"Selalu siap menerima tugas yang diberikan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"9","label":"Berani mengingatkan jika ada yang berbuat kesalahan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"10","label":"Disiplin waktu saat pelaksanaan pelatihan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"11","label":"Mematuhi semua aturan yang berlaku","kind":"Atd","trainee_feedback":null,"status":null},{"code":"12","label":"Tidak pernah mangkir","kind":"Atd","trainee_feedback":null,"status":null},{"code":"13","label":"Melaksanakan tugas kelompok bersama-sama","kind":"Atd","trainee_feedback":null,"status":null},{"code":"14","label":"Berinisiatif untuk membantu","kind":"Atd","trainee_feedback":null,"status":null},{"code":"15","label":"Selalu antusias jika diberi tugas","kind":"Atd","trainee_feedback":null,"status":null},{"code":"16","label":"Melaporkan setiap kejadian diluar wewenangnya","kind":"Atd","trainee_feedback":null,"status":null},{"code":"17","label":"Tidak ragu-ragu jika diberi instruksi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"18","label":"Mengoperasikan unit dengan penuh keyakinan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"19","label":"Bersikap proaktif di setiap kegiatan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"20","label":"Tidak malu untuk bertanya jika ada kesulitan","kind":"Atd","trainee_feedback":null,"status":null}]},"dumptruck":{"groups":[{"title":"Loading","subtitle":"Teknik pengambilan haluan, posisi terhadap alat muat, dan penggunaan transmisi\\/brake saat loading","items":[{"code":"1.1","label":"Pengambilan haluan untuk loading\\/ posisi antri\\/","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.2","label":"Posisi terhadap Alat muat (rata, aman & keras)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"1.3","label":"Tranmission \\"N\\" & Penggunaan brake saat loading","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.4","label":"Perhatian saat loading terhadap beban\\/payload meter (Khusus untuk Unit HDT)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"1.5","label":"Perhatian saat loading thd operator alat muat (Khusus untuk Unit LDT)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"1.6","label":"Perhatian terhadap standart muatan (Khusus untuk Unit LDT)","kind":"Knw","trainee_feedback":null,"status":null}]},{"title":"Hauling","subtitle":"Penggunaan speed\\/transmissi, clutch, shift limit, retarder, brake, dan keemihan mengemudi saat bergerak","items":[{"code":"2.1","label":"Penggunaan Speed\\/ Transmissi saat bergerak (Pastikan saat awal muatan harus dari F1 - Khusus HD)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.2","label":"Penggunaan Clutch (Khusus untuk Unit LDT)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.3","label":"Penggunaan Shift Limit & Power\\/ Eco Mode (Khusus untuk Unit HDT)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.4","label":"Penyesuaian tingkat kecepatan, RPM Engine & transmisi dengan kondisi medan (Jalan turunan, mendatar, dan tanjakan)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.5","label":"Penggunaan Retarder waktu turunan (Khusus untuk Unit HDT)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.6","label":"Penggunaan brake di turunan & menghentikan unit (Khusus untuk Unit LDT)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.7","label":"Pengembalian haluan saat membelok\\/ di tikungan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.8","label":"Ketrampilan\\/ kelembutan mengemudi","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.9","label":"Cycle time","kind":"Knw","trainee_feedback":null,"status":null}]},{"title":"Dumping","subtitle":"Teknik pengambilan haluan, posisi dumping, penggunaan brake, dan prosedur dumping\\/vessel","items":[{"code":"3.1","label":"Pengambilan haluan untuk dumping\\/manuver","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.2","label":"Posisi dumping (lokasi harus rata)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"3.3","label":"Penggunaan brake saat Dumping","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.4","label":"Prosedur Dumping (Penggunaan RPM)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"3.5","label":"Prosedur menurunkan Vessel","kind":"Knw","trainee_feedback":null,"status":null},{"code":"3.6","label":"Penempatan material yang tepat di disposal (Khusus untuk Unit HDT)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"3.7","label":"Prosedur menurunkan vesel di hopper\\/ stock pile (Khusus untuk Unit LDT)","kind":"Knw","trainee_feedback":null,"status":null}]}],"compliance":[{"code":"1","label":"Kesehatan fisik dan perlengkapan\\/ penggunaan APD","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2","label":"Menaiki dan menuruni unit (Three point contact)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3","label":"Penyetelan tempat duduk dan steering wheel","kind":"Skl","trainee_feedback":null,"status":null},{"code":"4","label":"Penggunaan sabuk pengaman\\/safety belt","kind":"Atd","trainee_feedback":null,"status":null},{"code":"5","label":"Penggunaan klakson dan lampu-lampu","kind":"Skl","trainee_feedback":null,"status":null},{"code":"6","label":"Keselamatan saat loading\\/harus didalam kabin","kind":"Atd","trainee_feedback":null,"status":null},{"code":"7","label":"Penyesuaian kecepatan terhadap kondisi medan (saat berpapasan, jalan licin, beiringan, kabut dan berdebu)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"8","label":"Kepedulian terhadap Rambu lalu lintas","kind":"Atd","trainee_feedback":null,"status":null},{"code":"9","label":"Keselamatan saat dumping","kind":"Skl","trainee_feedback":null,"status":null},{"code":"10","label":"Sopan santun mengemudi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"11","label":"Parkir unit ditempat yang rata (jarak antara unit dari samping kanan-kiri dan depan-belakang)","kind":"Skl","trainee_feedback":null,"status":null}],"behavior":[{"code":"1","label":"Mempedulikan pemakaian fuel\\/ bahan bakar","kind":"Atd","trainee_feedback":null,"status":null},{"code":"2","label":"Mempedulikan pemakaian tyre\\/ undercarriage","kind":"Atd","trainee_feedback":null,"status":null},{"code":"3","label":"Mempedulikan akan ketidaknormalan unit","kind":"Atd","trainee_feedback":null,"status":null},{"code":"4","label":"Mempedulikan untuk bekerja dengan efektif dan efisien","kind":"Atd","trainee_feedback":null,"status":null},{"code":"5","label":"Mempedulikan untuk meniadakan pemborosan dimanapun","kind":"Atd","trainee_feedback":null,"status":null},{"code":"6","label":"Melaksanakan aktivitas sesuai instruksi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"7","label":"Berusaha untuk melakukan yang terbaik","kind":"Atd","trainee_feedback":null,"status":null},{"code":"8","label":"Selalu siap menerima tugas yang diberikan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"9","label":"Berani mengingatkan jika ada yang berbuat kesalahan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"10","label":"Disiplin waktu saat pelaksanaan pelatihan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"11","label":"Mematuhi semua aturan yang berlaku","kind":"Atd","trainee_feedback":null,"status":null},{"code":"12","label":"Tidak pernah mangkir","kind":"Atd","trainee_feedback":null,"status":null},{"code":"13","label":"Melaksanakan tugas kelompok bersama-sama","kind":"Atd","trainee_feedback":null,"status":null},{"code":"14","label":"Berinisiatif untuk membantu","kind":"Atd","trainee_feedback":null,"status":null},{"code":"15","label":"Selalu antusias jika diberi tugas","kind":"Atd","trainee_feedback":null,"status":null},{"code":"16","label":"Melaporkan setiap kejadian diluar wewenangnya","kind":"Atd","trainee_feedback":null,"status":null},{"code":"17","label":"Tidak ragu-ragu jika diberi instruksi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"18","label":"Mengoperasikan unit dengan penuh keyakinan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"19","label":"Bersikap proaktif di setiap kegiatan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"20","label":"Tidak malu untuk bertanya jika ada kesulitan","kind":"Atd","trainee_feedback":null,"status":null}]},"semidump":{"groups":[{"title":"Loading","subtitle":"Teknik penempatan posisi, posisi trailer terhadap alat muat, dan penggunaan transmisi\\/brake saat loading","items":[{"code":"1.1","label":"Penempatan posisi untuk loading\\/ posisi antri","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.2","label":"Posisi Trailer terhadap Alat muat (rata & aman)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"1.3","label":"Transmission \\"N\\" & Penggunaan Parking Brake saat loading","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.4","label":"Perhatian saat loading terhadap beban\\/ vessel penuh","kind":"Knw","trainee_feedback":null,"status":null}]},{"title":"Hauling","subtitle":"Penggunaan speed\\/transmisi, power\\/offroad mode, trailer brake, dan keemihan mengemudi saat bergerak","items":[{"code":"2.1","label":"Penggunaan Speed\\/ Transmissi saat bergerak (Pastikan saat awal muatan harus dari C low)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.2","label":"Penggunaan Power\\/ Offroad Mode","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.3","label":"Penyesuaian tingkat kecepatan dengan kondisi medan (jalan turunan, mendatar, dan tanjakan)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.4","label":"Penggunaan Trailer Brake","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.5","label":"Pengembalian haluan saat membelok\\/ditikungan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.6","label":"Ketrampilan\\/kelembutan mengemudi","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.7","label":"Cycle time","kind":"Skl","trainee_feedback":null,"status":null}]},{"title":"Dumping","subtitle":"Teknik pengambilan haluan, posisi dumping, penggunaan brake, dan prosedur dumping\\/vessel","items":[{"code":"3.1","label":"Pengambilan haluan untuk dumping\\/ manuver","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.2","label":"Posisi dumping (lokasi harus rata)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"3.3","label":"Penggunaan brake saat Dumping","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.4","label":"Prosedur Dumping (Penggunaan RPM)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"3.5","label":"Prosedur menurunkan Vessel","kind":"Knw","trainee_feedback":null,"status":null},{"code":"3.6","label":"Penempatan material yang tepat di Hopper\\/Stock Pile","kind":"Knw","trainee_feedback":null,"status":null}]}],"compliance":[{"code":"1","label":"Kesehatan fisik dan perlengkapan\\/ penggunaan APD","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2","label":"Menaiki dan menuruni unit (three point contact)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3","label":"Penyetelan tempat duduk dan steering wheel","kind":"Skl","trainee_feedback":null,"status":null},{"code":"4","label":"Penggunaan sabuk pengaman\\/ safety belt","kind":"Atd","trainee_feedback":null,"status":null},{"code":"5","label":"Penggunaan klakson dan lampu-lampu","kind":"Skl","trainee_feedback":null,"status":null},{"code":"6","label":"Keselamatan saat loading\\/ harus didalam kabin","kind":"Atd","trainee_feedback":null,"status":null},{"code":"7","label":"Penyesuaian kecepatan terhadap kondisi medan (saat berpapasan, jalan licin, beiringan, kabut dan berdebu)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"8","label":"Kepedulian terhadap rambu lalu lintas","kind":"Atd","trainee_feedback":null,"status":null},{"code":"9","label":"Keselamatan saat dumping","kind":"Skl","trainee_feedback":null,"status":null},{"code":"10","label":"Sopan santun mengemudi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"11","label":"Parkir unit ditempat yang rata (jarak antara unit dari samping kanan-kiri dan depan-belakang)","kind":"Skl","trainee_feedback":null,"status":null}],"behavior":[{"code":"1","label":"Mempedulikan pemakaian fuel\\/ bahan bakar","kind":"Atd","trainee_feedback":null,"status":null},{"code":"2","label":"Mempedulikan pemakaian tyre\\/ undercarriage","kind":"Atd","trainee_feedback":null,"status":null},{"code":"3","label":"Mempedulikan akan ketidaknormalan unit","kind":"Atd","trainee_feedback":null,"status":null},{"code":"4","label":"Mempedulikan untuk bekerja dengan efektif dan efisien","kind":"Atd","trainee_feedback":null,"status":null},{"code":"5","label":"Mempedulikan untuk meniadakan pemborosan dimanapun","kind":"Atd","trainee_feedback":null,"status":null},{"code":"6","label":"Melaksanakan aktivitas sesuai instruksi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"7","label":"Berusaha untuk melakukan yang terbaik","kind":"Atd","trainee_feedback":null,"status":null},{"code":"8","label":"Selalu siap menerima tugas yang diberikan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"9","label":"Berani mengingatkan jika ada yang berbuat kesalahan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"10","label":"Disiplin waktu saat pelaksanaan pelatihan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"11","label":"Mematuhi semua aturan yang berlaku","kind":"Atd","trainee_feedback":null,"status":null},{"code":"12","label":"Tidak pernah mangkir","kind":"Atd","trainee_feedback":null,"status":null},{"code":"13","label":"Melaksanakan tugas kelompok bersama-sama","kind":"Atd","trainee_feedback":null,"status":null},{"code":"14","label":"Berinisiatif untuk membantu","kind":"Atd","trainee_feedback":null,"status":null},{"code":"15","label":"Selalu antusias jika diberi tugas","kind":"Atd","trainee_feedback":null,"status":null},{"code":"16","label":"Melaporkan setiap kejadian diluar wewenangnya","kind":"Atd","trainee_feedback":null,"status":null},{"code":"17","label":"Tidak ragu-ragu jika diberi instruksi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"18","label":"Mengoperasikan unit dengan penuh keyakinan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"19","label":"Bersikap proaktif di setiap kegiatan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"20","label":"Tidak malu untuk bertanya jika ada kesulitan","kind":"Atd","trainee_feedback":null,"status":null}]},"wheelloader":{"groups":[{"title":"Traveling","subtitle":"Pengoperasian wheel loader saat bergerak: speed, manuver, dan pemilihan jalur","items":[{"code":"1.1","label":"Memposisikan attachment dengan benar","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.2","label":"Penyesuaian speed dengan kondisi medan","kind":"Skl","trainee_feedback":null,"status":null}]}]}}	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
20	LOG-202608-0014	6	6	\N	\N	2026-09-02	day	BMO 1	\N	\N	1402.0	1410.0	8.0	final_approved	\N	\N	\N	\N	2026-09-10 10:39:00	2026-09-10 10:39:00	{"meta":{"unit_type":"DZ"},"track":{"groups":[{"items":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]},{"items":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":null,"trainee_feedback":"N\\/A"},{"status":4,"trainee_feedback":null}]},{"items":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]},{"items":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]}],"compliance":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}],"behavior":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]},"excavator":{"groups":[{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]}],"compliance":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}],"behavior":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},"dumptruck":{"groups":[{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]}],"compliance":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}],"behavior":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},"semidump":{"groups":[{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]}],"compliance":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}],"behavior":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},"wheelloader":{"groups":[{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]}]}}	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
21	LOG-202608-0015	6	6	\N	\N	2026-09-03	day	BMO 1	\N	\N	1410.0	1418.0	8.0	final_approved	\N	\N	\N	\N	2026-09-10 10:39:00	2026-09-10 10:39:00	{"meta":{"category_code":"DZ","unit_family":"track","trainee_name":"Henky Udiansyah","certification":"Green","company":"PT Maju Mundur","sticker_expired_at":"2026-12-31","assessment_mode":"pendampingan","assessment_stage_detail":null,"unit_type":"DZ"},"track":{"groups":[{"title":"Dozing & Digging untuk Unit (DZ) \\/ Grading & Digging untuk Unit (GR)","subtitle":"Cara memosisisikan blade, menggali, dan mendorong material","items":[{"code":"1.1","label":"Cara memposisikan Blade pada saat mendorong\\/ grading","kind":"Skl","status":4,"trainee_feedback":null},{"code":"1.2","label":"Penggunaan Tilt Blade","kind":"Knw","status":4,"trainee_feedback":null},{"code":"1.3","label":"Cara Pengoperasian blade untuk mendorong\\/ ditching","kind":"Skl","status":4,"trainee_feedback":null},{"code":"1.4","label":"Cara Pengoperasian blade untuk menggali\\/sloping","kind":"Skl","status":4,"trainee_feedback":null},{"code":"1.5","label":"Penyesuaian beban dengan rpm\\/posisi transmissi","kind":"Skl","status":4,"trainee_feedback":null},{"code":"1.6","label":"Teknik dozing\\/grading\\/digging","kind":"Skl","status":4,"trainee_feedback":null}]},{"title":"Spreading & Leveling","subtitle":"Pengoperasian untuk meratakan, memadatkan, dan membentuk area kerja","items":[{"code":"2.1","label":"Penggunaan Speed\\/ Transmissi saat bergerak","kind":"Knw","status":4,"trainee_feedback":null},{"code":"2.2","label":"Cara leveling menggunakan tilt","kind":"Knw","status":4,"trainee_feedback":null},{"code":"2.3","label":"Cara menghampar material untuk membuat jalan, menimbun lubang dll","kind":"Skl","status":4,"trainee_feedback":null},{"code":"2.4","label":"Filling pada saat melevelkan area kerja","kind":"Skl","status":4,"trainee_feedback":null},{"code":"2.5","label":"Penggunaan Steering","kind":"Skl","status":4,"trainee_feedback":null},{"code":"2.6","label":"Penggunaan Articulated (Khusus untuk unit GR)","kind":"Skl","status":4,"trainee_feedback":null},{"code":"2.7","label":"Teknik spreading\\/levelling","kind":"Skl","status":4,"trainee_feedback":null}]},{"title":"Ripping","subtitle":"Khusus untuk pekerjaan ripping dan pembukaan material keras","items":[{"code":"3.1","label":"Cara memposisikan Ripper","kind":"Skl","status":4,"trainee_feedback":null},{"code":"3.2","label":"Teknik Penetrasi Ripping","kind":"Knw","status":4,"trainee_feedback":null},{"code":"3.3","label":"Penyesuaian posisi ripper dengan kekerasan material","kind":"Skl","status":4,"trainee_feedback":null}]},{"title":"Finishing","subtitle":"Finishing grading dan koreksi permukaan kerja","items":[{"code":"4.1","label":"Kesesuaian penggunaan speed","kind":"Skl","status":4,"trainee_feedback":null},{"code":"4.2","label":"Hasil akhir pendorongan (hasil pekerjaan)","kind":"Skl","status":4,"trainee_feedback":null}]}],"compliance":[{"code":"1","label":"Kesehatan fisik dan perlengkapan\\/penggunaan APD","kind":"Knw","status":4,"trainee_feedback":null},{"code":"2","label":"Menaiki dan menuruni Unit (Three point contact)","kind":"Skl","status":4,"trainee_feedback":null},{"code":"3","label":"Penyetelan tempat duduk","kind":"Skl","status":4,"trainee_feedback":null},{"code":"4","label":"Penggunaan sabuk pengaman\\/ safety belt","kind":"Atd","status":4,"trainee_feedback":null},{"code":"5","label":"Penggunaan klakson dan lampu-lampu","kind":"Skl","status":4,"trainee_feedback":null},{"code":"6","label":"Keselamatan saat digging, dozing, spreading, levelling, ripping, travelling","kind":"Skl","status":4,"trainee_feedback":null},{"code":"7","label":"Penyesuaian jenis alat dengan lokasi pekerjaan","kind":"Skl","status":4,"trainee_feedback":null},{"code":"8","label":"Kepedulian terhadap patok-patok survey dan rambu","kind":"Atd","status":4,"trainee_feedback":null},{"code":"9","label":"Parkir unit ditempat yang rata dan aman (pasang lock dan cara meletakkan attachment)","kind":"Skl","status":4,"trainee_feedback":null},{"code":"10","label":"Keselamatan selama operasi","kind":"Knw","status":4,"trainee_feedback":null}],"behavior":[{"code":"1","label":"Mempedulikan pemakaian fuel\\/ bahan bakar","kind":"Atd","status":4,"trainee_feedback":null},{"code":"2","label":"Mempedulikan pemakaian tyre\\/ undercarriage","kind":"Atd","status":4,"trainee_feedback":null},{"code":"3","label":"Mempedulikan akan ketidaknormalan unit","kind":"Atd","status":4,"trainee_feedback":null},{"code":"4","label":"Mempedulikan untuk bekerja dengan efektif dan efisien","kind":"Atd","status":4,"trainee_feedback":null},{"code":"5","label":"Mempedulikan untuk meniadakan pemborosan dimanapun","kind":"Atd","status":4,"trainee_feedback":null},{"code":"6","label":"Melaksanakan aktivitas sesuai instruksi","kind":"Atd","status":4,"trainee_feedback":null},{"code":"7","label":"Berusaha untuk melakukan yang terbaik","kind":"Atd","status":4,"trainee_feedback":null},{"code":"8","label":"Selalu siap menerima tugas yang diberikan","kind":"Atd","status":4,"trainee_feedback":null},{"code":"9","label":"Berani mengingatkan jika ada yang berbuat kesalahan","kind":"Atd","status":4,"trainee_feedback":null},{"code":"10","label":"Disiplin waktu saat pelaksanaan pelatihan","kind":"Atd","status":4,"trainee_feedback":null},{"code":"11","label":"Mematuhi semua aturan yang berlaku","kind":"Atd","status":4,"trainee_feedback":null},{"code":"12","label":"Tidak pernah mangkir","kind":"Atd","status":4,"trainee_feedback":null},{"code":"13","label":"Melaksanakan tugas kelompok bersama-sama","kind":"Atd","status":4,"trainee_feedback":null},{"code":"14","label":"Berinisiatif untuk membantu","kind":"Atd","status":4,"trainee_feedback":null},{"code":"15","label":"Selalu antusias jika diberi tugas","kind":"Atd","status":4,"trainee_feedback":null},{"code":"16","label":"Melaporkan setiap kejadian diluar wewenangnya","kind":"Atd","status":4,"trainee_feedback":null},{"code":"17","label":"Tidak ragu-ragu jika diberi instruksi","kind":"Atd","status":4,"trainee_feedback":null},{"code":"18","label":"Mengoperasikan unit dengan penuh keyakinan","kind":"Atd","status":4,"trainee_feedback":null},{"code":"19","label":"Bersikap proaktif di setiap kegiatan","kind":"Atd","status":4,"trainee_feedback":null},{"code":"20","label":"Tidak malu untuk bertanya jika ada kesulitan","kind":"Atd","status":4,"trainee_feedback":null}]},"excavator":{"groups":[{"title":"Positioning","subtitle":"Cara memposisikan unit, track, dan upper structure di front loading","items":[{"code":"1.1","label":"Cara memposisikan unit di front loading","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.2","label":"Cara membuat landasan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.3","label":"Cara mengatur track dan upper structure","kind":"Skl","trainee_feedback":null,"status":null}]},{"title":"Loading & Dumping","subtitle":"Cara swing, memuat, dan dumping yang aman","items":[{"code":"2.1","label":"Cara swing muatan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.2","label":"Cara swing kosongan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.3","label":"Kombinasi gerakan","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.4","label":"Cara dumping dan kerapihan muatan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.5","label":"Sudut swing","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.6","label":"Cycle time","kind":"Knw","trainee_feedback":null,"status":null}]},{"title":"Digging","subtitle":"Teknik digging dan pengaturan kerja bucket","items":[{"code":"3.1","label":"Teknik digging (urutan pengambilan)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.2","label":"Sudut pengambilan (digging)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.3","label":"Gerakan kombinasi pada saat digging","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.4","label":"Volume bucket","kind":"Knw","trainee_feedback":null,"status":null}]},{"title":"Sloping","subtitle":"Teknik pembuatan slope dan kerapihan permukaan","items":[{"code":"4.1","label":"Teknik pembuatan slope","kind":"Skl","trainee_feedback":null,"status":null},{"code":"4.2","label":"Kerapihan slope","kind":"Skl","trainee_feedback":null,"status":null}]}],"compliance":[{"code":"1","label":"Kesehatan fisik dan perlengkapan\\/ penggunaan APD","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2","label":"Menaiki dan menuruni Unit (Three point contact)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3","label":"Penyetelan tempat duduk","kind":"Skl","trainee_feedback":null,"status":null},{"code":"4","label":"Penggunaan sabuk pengaman\\/safety belt","kind":"Atd","trainee_feedback":null,"status":null},{"code":"5","label":"Penggunaan klakson dan lampu-lampu","kind":"Skl","trainee_feedback":null,"status":null},{"code":"6","label":"Keselamatan saat loading, unloading, positioning, traveling, dan digging","kind":"Skl","trainee_feedback":null,"status":null},{"code":"7","label":"Penyesuaian jenis alat dengan lokasi pekerjaan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"8","label":"Kepedulian terhadap patok-patok survey dan rambu","kind":"Atd","trainee_feedback":null,"status":null},{"code":"9","label":"Parkir unit ditempat yang rata dan aman (pasang lock dan cara meletakkan attachment)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"10","label":"Keselamatan selama operasi","kind":"Knw","trainee_feedback":null,"status":null}],"behavior":[{"code":"1","label":"Mempedulikan pemakaian fuel\\/ bahan bakar","kind":"Atd","trainee_feedback":null,"status":null},{"code":"2","label":"Mempedulikan pemakaian tyre\\/ undercarriage","kind":"Atd","trainee_feedback":null,"status":null},{"code":"3","label":"Mempedulikan akan ketidaknormalan unit","kind":"Atd","trainee_feedback":null,"status":null},{"code":"4","label":"Mempedulikan untuk bekerja dengan efektif dan efisien","kind":"Atd","trainee_feedback":null,"status":null},{"code":"5","label":"Mempedulikan untuk meniadakan pemborosan dimanapun","kind":"Atd","trainee_feedback":null,"status":null},{"code":"6","label":"Melaksanakan aktivitas sesuai instruksi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"7","label":"Berusaha untuk melakukan yang terbaik","kind":"Atd","trainee_feedback":null,"status":null},{"code":"8","label":"Selalu siap menerima tugas yang diberikan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"9","label":"Berani mengingatkan jika ada yang berbuat kesalahan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"10","label":"Disiplin waktu saat pelaksanaan pelatihan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"11","label":"Mematuhi semua aturan yang berlaku","kind":"Atd","trainee_feedback":null,"status":null},{"code":"12","label":"Tidak pernah mangkir","kind":"Atd","trainee_feedback":null,"status":null},{"code":"13","label":"Melaksanakan tugas kelompok bersama-sama","kind":"Atd","trainee_feedback":null,"status":null},{"code":"14","label":"Berinisiatif untuk membantu","kind":"Atd","trainee_feedback":null,"status":null},{"code":"15","label":"Selalu antusias jika diberi tugas","kind":"Atd","trainee_feedback":null,"status":null},{"code":"16","label":"Melaporkan setiap kejadian diluar wewenangnya","kind":"Atd","trainee_feedback":null,"status":null},{"code":"17","label":"Tidak ragu-ragu jika diberi instruksi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"18","label":"Mengoperasikan unit dengan penuh keyakinan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"19","label":"Bersikap proaktif di setiap kegiatan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"20","label":"Tidak malu untuk bertanya jika ada kesulitan","kind":"Atd","trainee_feedback":null,"status":null}]},"dumptruck":{"groups":[{"title":"Loading","subtitle":"Teknik pengambilan haluan, posisi terhadap alat muat, dan penggunaan transmisi\\/brake saat loading","items":[{"code":"1.1","label":"Pengambilan haluan untuk loading\\/ posisi antri\\/","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.2","label":"Posisi terhadap Alat muat (rata, aman & keras)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"1.3","label":"Tranmission \\"N\\" & Penggunaan brake saat loading","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.4","label":"Perhatian saat loading terhadap beban\\/payload meter (Khusus untuk Unit HDT)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"1.5","label":"Perhatian saat loading thd operator alat muat (Khusus untuk Unit LDT)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"1.6","label":"Perhatian terhadap standart muatan (Khusus untuk Unit LDT)","kind":"Knw","trainee_feedback":null,"status":null}]},{"title":"Hauling","subtitle":"Penggunaan speed\\/transmissi, clutch, shift limit, retarder, brake, dan keemihan mengemudi saat bergerak","items":[{"code":"2.1","label":"Penggunaan Speed\\/ Transmissi saat bergerak (Pastikan saat awal muatan harus dari F1 - Khusus HD)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.2","label":"Penggunaan Clutch (Khusus untuk Unit LDT)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.3","label":"Penggunaan Shift Limit & Power\\/ Eco Mode (Khusus untuk Unit HDT)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.4","label":"Penyesuaian tingkat kecepatan, RPM Engine & transmisi dengan kondisi medan (Jalan turunan, mendatar, dan tanjakan)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.5","label":"Penggunaan Retarder waktu turunan (Khusus untuk Unit HDT)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.6","label":"Penggunaan brake di turunan & menghentikan unit (Khusus untuk Unit LDT)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.7","label":"Pengembalian haluan saat membelok\\/ di tikungan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.8","label":"Ketrampilan\\/ kelembutan mengemudi","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.9","label":"Cycle time","kind":"Knw","trainee_feedback":null,"status":null}]},{"title":"Dumping","subtitle":"Teknik pengambilan haluan, posisi dumping, penggunaan brake, dan prosedur dumping\\/vessel","items":[{"code":"3.1","label":"Pengambilan haluan untuk dumping\\/manuver","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.2","label":"Posisi dumping (lokasi harus rata)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"3.3","label":"Penggunaan brake saat Dumping","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.4","label":"Prosedur Dumping (Penggunaan RPM)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"3.5","label":"Prosedur menurunkan Vessel","kind":"Knw","trainee_feedback":null,"status":null},{"code":"3.6","label":"Penempatan material yang tepat di disposal (Khusus untuk Unit HDT)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"3.7","label":"Prosedur menurunkan vesel di hopper\\/ stock pile (Khusus untuk Unit LDT)","kind":"Knw","trainee_feedback":null,"status":null}]}],"compliance":[{"code":"1","label":"Kesehatan fisik dan perlengkapan\\/ penggunaan APD","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2","label":"Menaiki dan menuruni unit (Three point contact)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3","label":"Penyetelan tempat duduk dan steering wheel","kind":"Skl","trainee_feedback":null,"status":null},{"code":"4","label":"Penggunaan sabuk pengaman\\/safety belt","kind":"Atd","trainee_feedback":null,"status":null},{"code":"5","label":"Penggunaan klakson dan lampu-lampu","kind":"Skl","trainee_feedback":null,"status":null},{"code":"6","label":"Keselamatan saat loading\\/harus didalam kabin","kind":"Atd","trainee_feedback":null,"status":null},{"code":"7","label":"Penyesuaian kecepatan terhadap kondisi medan (saat berpapasan, jalan licin, beiringan, kabut dan berdebu)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"8","label":"Kepedulian terhadap Rambu lalu lintas","kind":"Atd","trainee_feedback":null,"status":null},{"code":"9","label":"Keselamatan saat dumping","kind":"Skl","trainee_feedback":null,"status":null},{"code":"10","label":"Sopan santun mengemudi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"11","label":"Parkir unit ditempat yang rata (jarak antara unit dari samping kanan-kiri dan depan-belakang)","kind":"Skl","trainee_feedback":null,"status":null}],"behavior":[{"code":"1","label":"Mempedulikan pemakaian fuel\\/ bahan bakar","kind":"Atd","trainee_feedback":null,"status":null},{"code":"2","label":"Mempedulikan pemakaian tyre\\/ undercarriage","kind":"Atd","trainee_feedback":null,"status":null},{"code":"3","label":"Mempedulikan akan ketidaknormalan unit","kind":"Atd","trainee_feedback":null,"status":null},{"code":"4","label":"Mempedulikan untuk bekerja dengan efektif dan efisien","kind":"Atd","trainee_feedback":null,"status":null},{"code":"5","label":"Mempedulikan untuk meniadakan pemborosan dimanapun","kind":"Atd","trainee_feedback":null,"status":null},{"code":"6","label":"Melaksanakan aktivitas sesuai instruksi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"7","label":"Berusaha untuk melakukan yang terbaik","kind":"Atd","trainee_feedback":null,"status":null},{"code":"8","label":"Selalu siap menerima tugas yang diberikan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"9","label":"Berani mengingatkan jika ada yang berbuat kesalahan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"10","label":"Disiplin waktu saat pelaksanaan pelatihan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"11","label":"Mematuhi semua aturan yang berlaku","kind":"Atd","trainee_feedback":null,"status":null},{"code":"12","label":"Tidak pernah mangkir","kind":"Atd","trainee_feedback":null,"status":null},{"code":"13","label":"Melaksanakan tugas kelompok bersama-sama","kind":"Atd","trainee_feedback":null,"status":null},{"code":"14","label":"Berinisiatif untuk membantu","kind":"Atd","trainee_feedback":null,"status":null},{"code":"15","label":"Selalu antusias jika diberi tugas","kind":"Atd","trainee_feedback":null,"status":null},{"code":"16","label":"Melaporkan setiap kejadian diluar wewenangnya","kind":"Atd","trainee_feedback":null,"status":null},{"code":"17","label":"Tidak ragu-ragu jika diberi instruksi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"18","label":"Mengoperasikan unit dengan penuh keyakinan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"19","label":"Bersikap proaktif di setiap kegiatan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"20","label":"Tidak malu untuk bertanya jika ada kesulitan","kind":"Atd","trainee_feedback":null,"status":null}]},"semidump":{"groups":[{"title":"Loading","subtitle":"Teknik penempatan posisi, posisi trailer terhadap alat muat, dan penggunaan transmisi\\/brake saat loading","items":[{"code":"1.1","label":"Penempatan posisi untuk loading\\/ posisi antri","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.2","label":"Posisi Trailer terhadap Alat muat (rata & aman)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"1.3","label":"Transmission \\"N\\" & Penggunaan Parking Brake saat loading","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.4","label":"Perhatian saat loading terhadap beban\\/ vessel penuh","kind":"Knw","trainee_feedback":null,"status":null}]},{"title":"Hauling","subtitle":"Penggunaan speed\\/transmisi, power\\/offroad mode, trailer brake, dan keemihan mengemudi saat bergerak","items":[{"code":"2.1","label":"Penggunaan Speed\\/ Transmissi saat bergerak (Pastikan saat awal muatan harus dari C low)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.2","label":"Penggunaan Power\\/ Offroad Mode","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.3","label":"Penyesuaian tingkat kecepatan dengan kondisi medan (jalan turunan, mendatar, dan tanjakan)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.4","label":"Penggunaan Trailer Brake","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.5","label":"Pengembalian haluan saat membelok\\/ditikungan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.6","label":"Ketrampilan\\/kelembutan mengemudi","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.7","label":"Cycle time","kind":"Skl","trainee_feedback":null,"status":null}]},{"title":"Dumping","subtitle":"Teknik pengambilan haluan, posisi dumping, penggunaan brake, dan prosedur dumping\\/vessel","items":[{"code":"3.1","label":"Pengambilan haluan untuk dumping\\/ manuver","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.2","label":"Posisi dumping (lokasi harus rata)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"3.3","label":"Penggunaan brake saat Dumping","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.4","label":"Prosedur Dumping (Penggunaan RPM)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"3.5","label":"Prosedur menurunkan Vessel","kind":"Knw","trainee_feedback":null,"status":null},{"code":"3.6","label":"Penempatan material yang tepat di Hopper\\/Stock Pile","kind":"Knw","trainee_feedback":null,"status":null}]}],"compliance":[{"code":"1","label":"Kesehatan fisik dan perlengkapan\\/ penggunaan APD","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2","label":"Menaiki dan menuruni unit (three point contact)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3","label":"Penyetelan tempat duduk dan steering wheel","kind":"Skl","trainee_feedback":null,"status":null},{"code":"4","label":"Penggunaan sabuk pengaman\\/ safety belt","kind":"Atd","trainee_feedback":null,"status":null},{"code":"5","label":"Penggunaan klakson dan lampu-lampu","kind":"Skl","trainee_feedback":null,"status":null},{"code":"6","label":"Keselamatan saat loading\\/ harus didalam kabin","kind":"Atd","trainee_feedback":null,"status":null},{"code":"7","label":"Penyesuaian kecepatan terhadap kondisi medan (saat berpapasan, jalan licin, beiringan, kabut dan berdebu)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"8","label":"Kepedulian terhadap rambu lalu lintas","kind":"Atd","trainee_feedback":null,"status":null},{"code":"9","label":"Keselamatan saat dumping","kind":"Skl","trainee_feedback":null,"status":null},{"code":"10","label":"Sopan santun mengemudi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"11","label":"Parkir unit ditempat yang rata (jarak antara unit dari samping kanan-kiri dan depan-belakang)","kind":"Skl","trainee_feedback":null,"status":null}],"behavior":[{"code":"1","label":"Mempedulikan pemakaian fuel\\/ bahan bakar","kind":"Atd","trainee_feedback":null,"status":null},{"code":"2","label":"Mempedulikan pemakaian tyre\\/ undercarriage","kind":"Atd","trainee_feedback":null,"status":null},{"code":"3","label":"Mempedulikan akan ketidaknormalan unit","kind":"Atd","trainee_feedback":null,"status":null},{"code":"4","label":"Mempedulikan untuk bekerja dengan efektif dan efisien","kind":"Atd","trainee_feedback":null,"status":null},{"code":"5","label":"Mempedulikan untuk meniadakan pemborosan dimanapun","kind":"Atd","trainee_feedback":null,"status":null},{"code":"6","label":"Melaksanakan aktivitas sesuai instruksi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"7","label":"Berusaha untuk melakukan yang terbaik","kind":"Atd","trainee_feedback":null,"status":null},{"code":"8","label":"Selalu siap menerima tugas yang diberikan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"9","label":"Berani mengingatkan jika ada yang berbuat kesalahan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"10","label":"Disiplin waktu saat pelaksanaan pelatihan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"11","label":"Mematuhi semua aturan yang berlaku","kind":"Atd","trainee_feedback":null,"status":null},{"code":"12","label":"Tidak pernah mangkir","kind":"Atd","trainee_feedback":null,"status":null},{"code":"13","label":"Melaksanakan tugas kelompok bersama-sama","kind":"Atd","trainee_feedback":null,"status":null},{"code":"14","label":"Berinisiatif untuk membantu","kind":"Atd","trainee_feedback":null,"status":null},{"code":"15","label":"Selalu antusias jika diberi tugas","kind":"Atd","trainee_feedback":null,"status":null},{"code":"16","label":"Melaporkan setiap kejadian diluar wewenangnya","kind":"Atd","trainee_feedback":null,"status":null},{"code":"17","label":"Tidak ragu-ragu jika diberi instruksi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"18","label":"Mengoperasikan unit dengan penuh keyakinan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"19","label":"Bersikap proaktif di setiap kegiatan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"20","label":"Tidak malu untuk bertanya jika ada kesulitan","kind":"Atd","trainee_feedback":null,"status":null}]},"wheelloader":{"groups":[{"title":"Traveling","subtitle":"Pengoperasian wheel loader saat bergerak: speed, manuver, dan pemilihan jalur","items":[{"code":"1.1","label":"Memposisikan attachment dengan benar","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.2","label":"Penyesuaian speed dengan kondisi medan","kind":"Skl","trainee_feedback":null,"status":null}]}]}}	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
22	LOG-202609-0016	6	\N	\N	\N	2026-09-01	day	SMO	\N	\N	1300.0	1308.0	8.0	final_approved	\N	\N	\N	\N	2026-09-10 10:39:00	2026-09-10 10:39:00	{"meta":{"category_code":"HDT","unit_family":"dumptruck","trainee_name":"Lionel Messi","certification":"Experience_internal","company":"PT Inter Miami","sticker_expired_at":"2026-12-31","assessment_mode":"pendampingan","assessment_stage_detail":null,"unit_type":"HDT"},"track":{"groups":[{"title":"Dozing & Digging untuk Unit (DZ) \\/ Grading & Digging untuk Unit (GR)","subtitle":"Cara memosisisikan blade, menggali, dan mendorong material","items":[{"code":"1.1","label":"Cara memposisikan Blade pada saat mendorong\\/ grading","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.2","label":"Penggunaan Tilt Blade","kind":"Knw","trainee_feedback":null,"status":null},{"code":"1.3","label":"Cara Pengoperasian blade untuk mendorong\\/ ditching","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.4","label":"Cara Pengoperasian blade untuk menggali\\/sloping","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.5","label":"Penyesuaian beban dengan rpm\\/posisi transmissi","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.6","label":"Teknik dozing\\/grading\\/digging","kind":"Skl","trainee_feedback":null,"status":null}]},{"title":"Spreading & Leveling","subtitle":"Pengoperasian untuk meratakan, memadatkan, dan membentuk area kerja","items":[{"code":"2.1","label":"Penggunaan Speed\\/ Transmissi saat bergerak","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.2","label":"Cara leveling menggunakan tilt","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.3","label":"Cara menghampar material untuk membuat jalan, menimbun lubang dll","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.4","label":"Filling pada saat melevelkan area kerja","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.5","label":"Penggunaan Steering","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.6","label":"Penggunaan Articulated (Khusus untuk unit GR)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.7","label":"Teknik spreading\\/levelling","kind":"Skl","trainee_feedback":null,"status":null}]},{"title":"Ripping","subtitle":"Khusus untuk pekerjaan ripping dan pembukaan material keras","items":[{"code":"3.1","label":"Cara memposisikan Ripper","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.2","label":"Teknik Penetrasi Ripping","kind":"Knw","trainee_feedback":null,"status":null},{"code":"3.3","label":"Penyesuaian posisi ripper dengan kekerasan material","kind":"Skl","trainee_feedback":null,"status":null}]},{"title":"Finishing","subtitle":"Finishing grading dan koreksi permukaan kerja","items":[{"code":"4.1","label":"Kesesuaian penggunaan speed","kind":"Skl","trainee_feedback":null,"status":null},{"code":"4.2","label":"Hasil akhir pendorongan (hasil pekerjaan)","kind":"Skl","trainee_feedback":null,"status":null}]}],"compliance":[{"code":"1","label":"Kesehatan fisik dan perlengkapan\\/penggunaan APD","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2","label":"Menaiki dan menuruni Unit (Three point contact)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3","label":"Penyetelan tempat duduk","kind":"Skl","trainee_feedback":null,"status":null},{"code":"4","label":"Penggunaan sabuk pengaman\\/ safety belt","kind":"Atd","trainee_feedback":null,"status":null},{"code":"5","label":"Penggunaan klakson dan lampu-lampu","kind":"Skl","trainee_feedback":null,"status":null},{"code":"6","label":"Keselamatan saat digging, dozing, spreading, levelling, ripping, travelling","kind":"Skl","trainee_feedback":null,"status":null},{"code":"7","label":"Penyesuaian jenis alat dengan lokasi pekerjaan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"8","label":"Kepedulian terhadap patok-patok survey dan rambu","kind":"Atd","trainee_feedback":null,"status":null},{"code":"9","label":"Parkir unit ditempat yang rata dan aman (pasang lock dan cara meletakkan attachment)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"10","label":"Keselamatan selama operasi","kind":"Knw","trainee_feedback":null,"status":null}],"behavior":[{"code":"1","label":"Mempedulikan pemakaian fuel\\/ bahan bakar","kind":"Atd","trainee_feedback":null,"status":null},{"code":"2","label":"Mempedulikan pemakaian tyre\\/ undercarriage","kind":"Atd","trainee_feedback":null,"status":null},{"code":"3","label":"Mempedulikan akan ketidaknormalan unit","kind":"Atd","trainee_feedback":null,"status":null},{"code":"4","label":"Mempedulikan untuk bekerja dengan efektif dan efisien","kind":"Atd","trainee_feedback":null,"status":null},{"code":"5","label":"Mempedulikan untuk meniadakan pemborosan dimanapun","kind":"Atd","trainee_feedback":null,"status":null},{"code":"6","label":"Melaksanakan aktivitas sesuai instruksi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"7","label":"Berusaha untuk melakukan yang terbaik","kind":"Atd","trainee_feedback":null,"status":null},{"code":"8","label":"Selalu siap menerima tugas yang diberikan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"9","label":"Berani mengingatkan jika ada yang berbuat kesalahan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"10","label":"Disiplin waktu saat pelaksanaan pelatihan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"11","label":"Mematuhi semua aturan yang berlaku","kind":"Atd","trainee_feedback":null,"status":null},{"code":"12","label":"Tidak pernah mangkir","kind":"Atd","trainee_feedback":null,"status":null},{"code":"13","label":"Melaksanakan tugas kelompok bersama-sama","kind":"Atd","trainee_feedback":null,"status":null},{"code":"14","label":"Berinisiatif untuk membantu","kind":"Atd","trainee_feedback":null,"status":null},{"code":"15","label":"Selalu antusias jika diberi tugas","kind":"Atd","trainee_feedback":null,"status":null},{"code":"16","label":"Melaporkan setiap kejadian diluar wewenangnya","kind":"Atd","trainee_feedback":null,"status":null},{"code":"17","label":"Tidak ragu-ragu jika diberi instruksi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"18","label":"Mengoperasikan unit dengan penuh keyakinan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"19","label":"Bersikap proaktif di setiap kegiatan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"20","label":"Tidak malu untuk bertanya jika ada kesulitan","kind":"Atd","trainee_feedback":null,"status":null}]},"excavator":{"groups":[{"title":"Positioning","subtitle":"Cara memposisikan unit, track, dan upper structure di front loading","items":[{"code":"1.1","label":"Cara memposisikan unit di front loading","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.2","label":"Cara membuat landasan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.3","label":"Cara mengatur track dan upper structure","kind":"Skl","trainee_feedback":null,"status":null}]},{"title":"Loading & Dumping","subtitle":"Cara swing, memuat, dan dumping yang aman","items":[{"code":"2.1","label":"Cara swing muatan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.2","label":"Cara swing kosongan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.3","label":"Kombinasi gerakan","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.4","label":"Cara dumping dan kerapihan muatan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.5","label":"Sudut swing","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.6","label":"Cycle time","kind":"Knw","trainee_feedback":null,"status":null}]},{"title":"Digging","subtitle":"Teknik digging dan pengaturan kerja bucket","items":[{"code":"3.1","label":"Teknik digging (urutan pengambilan)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.2","label":"Sudut pengambilan (digging)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.3","label":"Gerakan kombinasi pada saat digging","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.4","label":"Volume bucket","kind":"Knw","trainee_feedback":null,"status":null}]},{"title":"Sloping","subtitle":"Teknik pembuatan slope dan kerapihan permukaan","items":[{"code":"4.1","label":"Teknik pembuatan slope","kind":"Skl","trainee_feedback":null,"status":null},{"code":"4.2","label":"Kerapihan slope","kind":"Skl","trainee_feedback":null,"status":null}]}],"compliance":[{"code":"1","label":"Kesehatan fisik dan perlengkapan\\/ penggunaan APD","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2","label":"Menaiki dan menuruni Unit (Three point contact)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3","label":"Penyetelan tempat duduk","kind":"Skl","trainee_feedback":null,"status":null},{"code":"4","label":"Penggunaan sabuk pengaman\\/safety belt","kind":"Atd","trainee_feedback":null,"status":null},{"code":"5","label":"Penggunaan klakson dan lampu-lampu","kind":"Skl","trainee_feedback":null,"status":null},{"code":"6","label":"Keselamatan saat loading, unloading, positioning, traveling, dan digging","kind":"Skl","trainee_feedback":null,"status":null},{"code":"7","label":"Penyesuaian jenis alat dengan lokasi pekerjaan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"8","label":"Kepedulian terhadap patok-patok survey dan rambu","kind":"Atd","trainee_feedback":null,"status":null},{"code":"9","label":"Parkir unit ditempat yang rata dan aman (pasang lock dan cara meletakkan attachment)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"10","label":"Keselamatan selama operasi","kind":"Knw","trainee_feedback":null,"status":null}],"behavior":[{"code":"1","label":"Mempedulikan pemakaian fuel\\/ bahan bakar","kind":"Atd","trainee_feedback":null,"status":null},{"code":"2","label":"Mempedulikan pemakaian tyre\\/ undercarriage","kind":"Atd","trainee_feedback":null,"status":null},{"code":"3","label":"Mempedulikan akan ketidaknormalan unit","kind":"Atd","trainee_feedback":null,"status":null},{"code":"4","label":"Mempedulikan untuk bekerja dengan efektif dan efisien","kind":"Atd","trainee_feedback":null,"status":null},{"code":"5","label":"Mempedulikan untuk meniadakan pemborosan dimanapun","kind":"Atd","trainee_feedback":null,"status":null},{"code":"6","label":"Melaksanakan aktivitas sesuai instruksi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"7","label":"Berusaha untuk melakukan yang terbaik","kind":"Atd","trainee_feedback":null,"status":null},{"code":"8","label":"Selalu siap menerima tugas yang diberikan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"9","label":"Berani mengingatkan jika ada yang berbuat kesalahan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"10","label":"Disiplin waktu saat pelaksanaan pelatihan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"11","label":"Mematuhi semua aturan yang berlaku","kind":"Atd","trainee_feedback":null,"status":null},{"code":"12","label":"Tidak pernah mangkir","kind":"Atd","trainee_feedback":null,"status":null},{"code":"13","label":"Melaksanakan tugas kelompok bersama-sama","kind":"Atd","trainee_feedback":null,"status":null},{"code":"14","label":"Berinisiatif untuk membantu","kind":"Atd","trainee_feedback":null,"status":null},{"code":"15","label":"Selalu antusias jika diberi tugas","kind":"Atd","trainee_feedback":null,"status":null},{"code":"16","label":"Melaporkan setiap kejadian diluar wewenangnya","kind":"Atd","trainee_feedback":null,"status":null},{"code":"17","label":"Tidak ragu-ragu jika diberi instruksi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"18","label":"Mengoperasikan unit dengan penuh keyakinan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"19","label":"Bersikap proaktif di setiap kegiatan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"20","label":"Tidak malu untuk bertanya jika ada kesulitan","kind":"Atd","trainee_feedback":null,"status":null}]},"dumptruck":{"groups":[{"title":"Loading","subtitle":"Teknik pengambilan haluan, posisi terhadap alat muat, dan penggunaan transmisi\\/brake saat loading","items":[{"code":"1.1","label":"Pengambilan haluan untuk loading\\/ posisi antri\\/","kind":"Skl","status":4,"trainee_feedback":null},{"code":"1.2","label":"Posisi terhadap Alat muat (rata, aman & keras)","kind":"Knw","status":4,"trainee_feedback":null},{"code":"1.3","label":"Tranmission \\"N\\" & Penggunaan brake saat loading","kind":"Skl","status":4,"trainee_feedback":null},{"code":"1.4","label":"Perhatian saat loading terhadap beban\\/payload meter (Khusus untuk Unit HDT)","kind":"Knw","status":1,"trainee_feedback":"Tolong lebih diperhatikan lagi poin ini"},{"code":"1.5","label":"Perhatian saat loading thd operator alat muat (Khusus untuk Unit LDT)","kind":"Knw","trainee_feedback":"N\\/A","status":null},{"code":"1.6","label":"Perhatian terhadap standart muatan (Khusus untuk Unit LDT)","kind":"Knw","trainee_feedback":"N\\/A","status":null}]},{"title":"Hauling","subtitle":"Penggunaan speed\\/transmissi, clutch, shift limit, retarder, brake, dan keemihan mengemudi saat bergerak","items":[{"code":"2.1","label":"Penggunaan Speed\\/ Transmissi saat bergerak (Pastikan saat awal muatan harus dari F1 - Khusus HD)","kind":"Knw","status":1,"trainee_feedback":"Tolong lebih diperhatikan lagi poin ini"},{"code":"2.2","label":"Penggunaan Clutch (Khusus untuk Unit LDT)","kind":"Knw","trainee_feedback":"N\\/A","status":null},{"code":"2.3","label":"Penggunaan Shift Limit & Power\\/ Eco Mode (Khusus untuk Unit HDT)","kind":"Knw","status":1,"trainee_feedback":"Tolong lebih diperhatikan lagi poin ini"},{"code":"2.4","label":"Penyesuaian tingkat kecepatan, RPM Engine & transmisi dengan kondisi medan (Jalan turunan, mendatar, dan tanjakan)","kind":"Skl","status":4,"trainee_feedback":null},{"code":"2.5","label":"Penggunaan Retarder waktu turunan (Khusus untuk Unit HDT)","kind":"Skl","status":1,"trainee_feedback":"Tolong lebih diperhatikan lagi poin ini"},{"code":"2.6","label":"Penggunaan brake di turunan & menghentikan unit (Khusus untuk Unit LDT)","kind":"Skl","trainee_feedback":"N\\/A","status":null},{"code":"2.7","label":"Pengembalian haluan saat membelok\\/ di tikungan","kind":"Skl","status":4,"trainee_feedback":null},{"code":"2.8","label":"Ketrampilan\\/ kelembutan mengemudi","kind":"Skl","status":4,"trainee_feedback":null},{"code":"2.9","label":"Cycle time","kind":"Knw","status":4,"trainee_feedback":null}]},{"title":"Dumping","subtitle":"Teknik pengambilan haluan, posisi dumping, penggunaan brake, dan prosedur dumping\\/vessel","items":[{"code":"3.1","label":"Pengambilan haluan untuk dumping\\/manuver","kind":"Skl","status":4,"trainee_feedback":null},{"code":"3.2","label":"Posisi dumping (lokasi harus rata)","kind":"Knw","status":4,"trainee_feedback":null},{"code":"3.3","label":"Penggunaan brake saat Dumping","kind":"Skl","status":4,"trainee_feedback":null},{"code":"3.4","label":"Prosedur Dumping (Penggunaan RPM)","kind":"Knw","status":4,"trainee_feedback":null},{"code":"3.5","label":"Prosedur menurunkan Vessel","kind":"Knw","status":4,"trainee_feedback":null},{"code":"3.6","label":"Penempatan material yang tepat di disposal (Khusus untuk Unit HDT)","kind":"Knw","status":1,"trainee_feedback":"Tolong lebih diperhatikan lagi poin ini"},{"code":"3.7","label":"Prosedur menurunkan vesel di hopper\\/ stock pile (Khusus untuk Unit LDT)","kind":"Knw","trainee_feedback":"N\\/A","status":null}]}],"compliance":[{"code":"1","label":"Kesehatan fisik dan perlengkapan\\/ penggunaan APD","kind":"Knw","status":4,"trainee_feedback":null},{"code":"2","label":"Menaiki dan menuruni unit (Three point contact)","kind":"Skl","status":4,"trainee_feedback":null},{"code":"3","label":"Penyetelan tempat duduk dan steering wheel","kind":"Skl","status":4,"trainee_feedback":null},{"code":"4","label":"Penggunaan sabuk pengaman\\/safety belt","kind":"Atd","status":4,"trainee_feedback":null},{"code":"5","label":"Penggunaan klakson dan lampu-lampu","kind":"Skl","status":4,"trainee_feedback":null},{"code":"6","label":"Keselamatan saat loading\\/harus didalam kabin","kind":"Atd","status":4,"trainee_feedback":null},{"code":"7","label":"Penyesuaian kecepatan terhadap kondisi medan (saat berpapasan, jalan licin, beiringan, kabut dan berdebu)","kind":"Skl","status":4,"trainee_feedback":null},{"code":"8","label":"Kepedulian terhadap Rambu lalu lintas","kind":"Atd","status":4,"trainee_feedback":null},{"code":"9","label":"Keselamatan saat dumping","kind":"Skl","status":4,"trainee_feedback":null},{"code":"10","label":"Sopan santun mengemudi","kind":"Atd","status":4,"trainee_feedback":null},{"code":"11","label":"Parkir unit ditempat yang rata (jarak antara unit dari samping kanan-kiri dan depan-belakang)","kind":"Skl","status":4,"trainee_feedback":null}],"behavior":[{"code":"1","label":"Mempedulikan pemakaian fuel\\/ bahan bakar","kind":"Atd","status":4,"trainee_feedback":null},{"code":"2","label":"Mempedulikan pemakaian tyre\\/ undercarriage","kind":"Atd","status":4,"trainee_feedback":null},{"code":"3","label":"Mempedulikan akan ketidaknormalan unit","kind":"Atd","status":4,"trainee_feedback":null},{"code":"4","label":"Mempedulikan untuk bekerja dengan efektif dan efisien","kind":"Atd","status":4,"trainee_feedback":null},{"code":"5","label":"Mempedulikan untuk meniadakan pemborosan dimanapun","kind":"Atd","status":4,"trainee_feedback":null},{"code":"6","label":"Melaksanakan aktivitas sesuai instruksi","kind":"Atd","status":4,"trainee_feedback":null},{"code":"7","label":"Berusaha untuk melakukan yang terbaik","kind":"Atd","status":4,"trainee_feedback":null},{"code":"8","label":"Selalu siap menerima tugas yang diberikan","kind":"Atd","status":4,"trainee_feedback":null},{"code":"9","label":"Berani mengingatkan jika ada yang berbuat kesalahan","kind":"Atd","status":4,"trainee_feedback":null},{"code":"10","label":"Disiplin waktu saat pelaksanaan pelatihan","kind":"Atd","status":4,"trainee_feedback":null},{"code":"11","label":"Mematuhi semua aturan yang berlaku","kind":"Atd","status":4,"trainee_feedback":null},{"code":"12","label":"Tidak pernah mangkir","kind":"Atd","status":4,"trainee_feedback":null},{"code":"13","label":"Melaksanakan tugas kelompok bersama-sama","kind":"Atd","status":4,"trainee_feedback":null},{"code":"14","label":"Berinisiatif untuk membantu","kind":"Atd","status":4,"trainee_feedback":null},{"code":"15","label":"Selalu antusias jika diberi tugas","kind":"Atd","status":4,"trainee_feedback":null},{"code":"16","label":"Melaporkan setiap kejadian diluar wewenangnya","kind":"Atd","status":4,"trainee_feedback":null},{"code":"17","label":"Tidak ragu-ragu jika diberi instruksi","kind":"Atd","status":4,"trainee_feedback":null},{"code":"18","label":"Mengoperasikan unit dengan penuh keyakinan","kind":"Atd","status":4,"trainee_feedback":null},{"code":"19","label":"Bersikap proaktif di setiap kegiatan","kind":"Atd","status":4,"trainee_feedback":null},{"code":"20","label":"Tidak malu untuk bertanya jika ada kesulitan","kind":"Atd","status":4,"trainee_feedback":null}]},"semidump":{"groups":[{"title":"Loading","subtitle":"Teknik penempatan posisi, posisi trailer terhadap alat muat, dan penggunaan transmisi\\/brake saat loading","items":[{"code":"1.1","label":"Penempatan posisi untuk loading\\/ posisi antri","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.2","label":"Posisi Trailer terhadap Alat muat (rata & aman)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"1.3","label":"Transmission \\"N\\" & Penggunaan Parking Brake saat loading","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.4","label":"Perhatian saat loading terhadap beban\\/ vessel penuh","kind":"Knw","trainee_feedback":null,"status":null}]},{"title":"Hauling","subtitle":"Penggunaan speed\\/transmisi, power\\/offroad mode, trailer brake, dan keemihan mengemudi saat bergerak","items":[{"code":"2.1","label":"Penggunaan Speed\\/ Transmissi saat bergerak (Pastikan saat awal muatan harus dari C low)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.2","label":"Penggunaan Power\\/ Offroad Mode","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.3","label":"Penyesuaian tingkat kecepatan dengan kondisi medan (jalan turunan, mendatar, dan tanjakan)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.4","label":"Penggunaan Trailer Brake","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.5","label":"Pengembalian haluan saat membelok\\/ditikungan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.6","label":"Ketrampilan\\/kelembutan mengemudi","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.7","label":"Cycle time","kind":"Skl","trainee_feedback":null,"status":null}]},{"title":"Dumping","subtitle":"Teknik pengambilan haluan, posisi dumping, penggunaan brake, dan prosedur dumping\\/vessel","items":[{"code":"3.1","label":"Pengambilan haluan untuk dumping\\/ manuver","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.2","label":"Posisi dumping (lokasi harus rata)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"3.3","label":"Penggunaan brake saat Dumping","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.4","label":"Prosedur Dumping (Penggunaan RPM)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"3.5","label":"Prosedur menurunkan Vessel","kind":"Knw","trainee_feedback":null,"status":null},{"code":"3.6","label":"Penempatan material yang tepat di Hopper\\/Stock Pile","kind":"Knw","trainee_feedback":null,"status":null}]}],"compliance":[{"code":"1","label":"Kesehatan fisik dan perlengkapan\\/ penggunaan APD","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2","label":"Menaiki dan menuruni unit (three point contact)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3","label":"Penyetelan tempat duduk dan steering wheel","kind":"Skl","trainee_feedback":null,"status":null},{"code":"4","label":"Penggunaan sabuk pengaman\\/ safety belt","kind":"Atd","trainee_feedback":null,"status":null},{"code":"5","label":"Penggunaan klakson dan lampu-lampu","kind":"Skl","trainee_feedback":null,"status":null},{"code":"6","label":"Keselamatan saat loading\\/ harus didalam kabin","kind":"Atd","trainee_feedback":null,"status":null},{"code":"7","label":"Penyesuaian kecepatan terhadap kondisi medan (saat berpapasan, jalan licin, beiringan, kabut dan berdebu)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"8","label":"Kepedulian terhadap rambu lalu lintas","kind":"Atd","trainee_feedback":null,"status":null},{"code":"9","label":"Keselamatan saat dumping","kind":"Skl","trainee_feedback":null,"status":null},{"code":"10","label":"Sopan santun mengemudi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"11","label":"Parkir unit ditempat yang rata (jarak antara unit dari samping kanan-kiri dan depan-belakang)","kind":"Skl","trainee_feedback":null,"status":null}],"behavior":[{"code":"1","label":"Mempedulikan pemakaian fuel\\/ bahan bakar","kind":"Atd","trainee_feedback":null,"status":null},{"code":"2","label":"Mempedulikan pemakaian tyre\\/ undercarriage","kind":"Atd","trainee_feedback":null,"status":null},{"code":"3","label":"Mempedulikan akan ketidaknormalan unit","kind":"Atd","trainee_feedback":null,"status":null},{"code":"4","label":"Mempedulikan untuk bekerja dengan efektif dan efisien","kind":"Atd","trainee_feedback":null,"status":null},{"code":"5","label":"Mempedulikan untuk meniadakan pemborosan dimanapun","kind":"Atd","trainee_feedback":null,"status":null},{"code":"6","label":"Melaksanakan aktivitas sesuai instruksi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"7","label":"Berusaha untuk melakukan yang terbaik","kind":"Atd","trainee_feedback":null,"status":null},{"code":"8","label":"Selalu siap menerima tugas yang diberikan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"9","label":"Berani mengingatkan jika ada yang berbuat kesalahan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"10","label":"Disiplin waktu saat pelaksanaan pelatihan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"11","label":"Mematuhi semua aturan yang berlaku","kind":"Atd","trainee_feedback":null,"status":null},{"code":"12","label":"Tidak pernah mangkir","kind":"Atd","trainee_feedback":null,"status":null},{"code":"13","label":"Melaksanakan tugas kelompok bersama-sama","kind":"Atd","trainee_feedback":null,"status":null},{"code":"14","label":"Berinisiatif untuk membantu","kind":"Atd","trainee_feedback":null,"status":null},{"code":"15","label":"Selalu antusias jika diberi tugas","kind":"Atd","trainee_feedback":null,"status":null},{"code":"16","label":"Melaporkan setiap kejadian diluar wewenangnya","kind":"Atd","trainee_feedback":null,"status":null},{"code":"17","label":"Tidak ragu-ragu jika diberi instruksi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"18","label":"Mengoperasikan unit dengan penuh keyakinan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"19","label":"Bersikap proaktif di setiap kegiatan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"20","label":"Tidak malu untuk bertanya jika ada kesulitan","kind":"Atd","trainee_feedback":null,"status":null}]},"wheelloader":{"groups":[{"title":"Traveling","subtitle":"Pengoperasian wheel loader saat bergerak: speed, manuver, dan pemilihan jalur","items":[{"code":"1.1","label":"Memposisikan attachment dengan benar","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.2","label":"Penyesuaian speed dengan kondisi medan","kind":"Skl","status":null}]}]}}	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
32	LOG-202608-0048	16	\N	\N	\N	2026-08-28	day	BMO 1	\N	\N	1230.0	1240.0	10.0	final_approved	\N	\N	\N	\N	2026-09-10 10:40:29	2026-09-10 10:40:29	{"meta":{"unit_type":"DZ"},"track":{"groups":[{"items":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]},{"items":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":null,"trainee_feedback":"N\\/A"},{"status":4,"trainee_feedback":null}]},{"items":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]},{"items":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]}],"compliance":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}],"behavior":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]},"excavator":{"groups":[{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]}],"compliance":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}],"behavior":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},"dumptruck":{"groups":[{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]}],"compliance":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}],"behavior":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},"semidump":{"groups":[{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]}],"compliance":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}],"behavior":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},"wheelloader":{"groups":[{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]}]}}	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
33	LOG-202608-0049	16	\N	\N	\N	2026-08-31	day	BMO 1	\N	\N	1340.0	1386.0	46.0	final_approved	\N	\N	\N	\N	2026-09-10 10:40:29	2026-09-10 10:40:29	{"meta":{"unit_type":"DZ"},"track":{"groups":[{"items":[{"status":4,"trainee_feedback":null},{"status":2,"trainee_feedback":"Penggunaan Tilt Blade masih kasar"},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]},{"items":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":null,"trainee_feedback":"N\\/A"},{"status":4,"trainee_feedback":null}]},{"items":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]},{"items":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]}],"compliance":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}],"behavior":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]},"excavator":{"groups":[{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]}],"compliance":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}],"behavior":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},"dumptruck":{"groups":[{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]}],"compliance":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}],"behavior":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},"semidump":{"groups":[{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]}],"compliance":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}],"behavior":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},"wheelloader":{"groups":[{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]}]}}	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
34	LOG-202608-0050	16	\N	\N	\N	2026-08-31	night	BMO 1	\N	\N	1386.0	1394.0	8.0	final_approved	\N	\N	\N	\N	2026-09-10 10:40:29	2026-09-10 10:40:29	{"meta":{"unit_type":"DZ"},"track":{"groups":[{"items":[{"status":4,"trainee_feedback":null},{"status":2,"trainee_feedback":"Masih kasar"},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]},{"items":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]},{"items":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]},{"items":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]}],"compliance":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}],"behavior":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]},"excavator":{"groups":[{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]}],"compliance":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}],"behavior":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},"dumptruck":{"groups":[{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]}],"compliance":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}],"behavior":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},"semidump":{"groups":[{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]}],"compliance":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}],"behavior":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},"wheelloader":{"groups":[{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]}]}}	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
35	LOG-202608-0051	16	\N	\N	\N	2026-08-31	night	BMO 1	\N	\N	1394.0	1402.0	8.0	final_approved	\N	\N	\N	\N	2026-09-10 10:40:29	2026-09-10 10:40:29	{"meta":{"category_code":"DZ","unit_family":"track","trainee_name":"Henky Udiansyah","certification":"Green","company":"PT Maju Mundur","sticker_expired_at":"2026-12-31","assessment_mode":"pendampingan","assessment_stage_detail":null,"unit_type":"DZ"},"track":{"groups":[{"title":"Dozing & Digging untuk Unit (DZ) \\/ Grading & Digging untuk Unit (GR)","subtitle":"Cara memosisisikan blade, menggali, dan mendorong material","items":[{"code":"1.1","label":"Cara memposisikan Blade pada saat mendorong\\/ grading","kind":"Skl","status":4,"trainee_feedback":null},{"code":"1.2","label":"Penggunaan Tilt Blade","kind":"Knw","status":4,"trainee_feedback":null},{"code":"1.3","label":"Cara Pengoperasian blade untuk mendorong\\/ ditching","kind":"Skl","status":4,"trainee_feedback":null},{"code":"1.4","label":"Cara Pengoperasian blade untuk menggali\\/sloping","kind":"Skl","status":4,"trainee_feedback":null},{"code":"1.5","label":"Penyesuaian beban dengan rpm\\/posisi transmissi","kind":"Skl","status":4,"trainee_feedback":null},{"code":"1.6","label":"Teknik dozing\\/grading\\/digging","kind":"Skl","status":4,"trainee_feedback":null}]},{"title":"Spreading & Leveling","subtitle":"Pengoperasian untuk meratakan, memadatkan, dan membentuk area kerja","items":[{"code":"2.1","label":"Penggunaan Speed\\/ Transmissi saat bergerak","kind":"Knw","status":4,"trainee_feedback":null},{"code":"2.2","label":"Cara leveling menggunakan tilt","kind":"Knw","status":4,"trainee_feedback":null},{"code":"2.3","label":"Cara menghampar material untuk membuat jalan, menimbun lubang dll","kind":"Skl","status":4,"trainee_feedback":null},{"code":"2.4","label":"Filling pada saat melevelkan area kerja","kind":"Skl","status":4,"trainee_feedback":null},{"code":"2.5","label":"Penggunaan Steering","kind":"Skl","status":4,"trainee_feedback":null},{"code":"2.6","label":"Penggunaan Articulated (Khusus untuk unit GR)","kind":"Skl","trainee_feedback":"N\\/A","status":null},{"code":"2.7","label":"Teknik spreading\\/levelling","kind":"Skl","status":4,"trainee_feedback":null}]},{"title":"Ripping","subtitle":"Khusus untuk pekerjaan ripping dan pembukaan material keras","items":[{"code":"3.1","label":"Cara memposisikan Ripper","kind":"Skl","status":4,"trainee_feedback":null},{"code":"3.2","label":"Teknik Penetrasi Ripping","kind":"Knw","status":4,"trainee_feedback":null},{"code":"3.3","label":"Penyesuaian posisi ripper dengan kekerasan material","kind":"Skl","status":4,"trainee_feedback":null}]},{"title":"Finishing","subtitle":"Finishing grading dan koreksi permukaan kerja","items":[{"code":"4.1","label":"Kesesuaian penggunaan speed","kind":"Skl","status":4,"trainee_feedback":null},{"code":"4.2","label":"Hasil akhir pendorongan (hasil pekerjaan)","kind":"Skl","status":4,"trainee_feedback":null}]}],"compliance":[{"code":"1","label":"Kesehatan fisik dan perlengkapan\\/penggunaan APD","kind":"Knw","status":4,"trainee_feedback":null},{"code":"2","label":"Menaiki dan menuruni Unit (Three point contact)","kind":"Skl","status":4,"trainee_feedback":null},{"code":"3","label":"Penyetelan tempat duduk","kind":"Skl","status":4,"trainee_feedback":null},{"code":"4","label":"Penggunaan sabuk pengaman\\/ safety belt","kind":"Atd","status":4,"trainee_feedback":null},{"code":"5","label":"Penggunaan klakson dan lampu-lampu","kind":"Skl","status":4,"trainee_feedback":null},{"code":"6","label":"Keselamatan saat digging, dozing, spreading, levelling, ripping, travelling","kind":"Skl","status":4,"trainee_feedback":null},{"code":"7","label":"Penyesuaian jenis alat dengan lokasi pekerjaan","kind":"Skl","status":4,"trainee_feedback":null},{"code":"8","label":"Kepedulian terhadap patok-patok survey dan rambu","kind":"Atd","status":4,"trainee_feedback":null},{"code":"9","label":"Parkir unit ditempat yang rata dan aman (pasang lock dan cara meletakkan attachment)","kind":"Skl","status":4,"trainee_feedback":null},{"code":"10","label":"Keselamatan selama operasi","kind":"Knw","status":4,"trainee_feedback":null}],"behavior":[{"code":"1","label":"Mempedulikan pemakaian fuel\\/ bahan bakar","kind":"Atd","status":4,"trainee_feedback":null},{"code":"2","label":"Mempedulikan pemakaian tyre\\/ undercarriage","kind":"Atd","status":4,"trainee_feedback":null},{"code":"3","label":"Mempedulikan akan ketidaknormalan unit","kind":"Atd","status":4,"trainee_feedback":null},{"code":"4","label":"Mempedulikan untuk bekerja dengan efektif dan efisien","kind":"Atd","status":4,"trainee_feedback":null},{"code":"5","label":"Mempedulikan untuk meniadakan pemborosan dimanapun","kind":"Atd","status":4,"trainee_feedback":null},{"code":"6","label":"Melaksanakan aktivitas sesuai instruksi","kind":"Atd","status":4,"trainee_feedback":null},{"code":"7","label":"Berusaha untuk melakukan yang terbaik","kind":"Atd","status":4,"trainee_feedback":null},{"code":"8","label":"Selalu siap menerima tugas yang diberikan","kind":"Atd","status":4,"trainee_feedback":null},{"code":"9","label":"Berani mengingatkan jika ada yang berbuat kesalahan","kind":"Atd","status":4,"trainee_feedback":null},{"code":"10","label":"Disiplin waktu saat pelaksanaan pelatihan","kind":"Atd","status":4,"trainee_feedback":null},{"code":"11","label":"Mematuhi semua aturan yang berlaku","kind":"Atd","status":4,"trainee_feedback":null},{"code":"12","label":"Tidak pernah mangkir","kind":"Atd","status":4,"trainee_feedback":null},{"code":"13","label":"Melaksanakan tugas kelompok bersama-sama","kind":"Atd","status":4,"trainee_feedback":null},{"code":"14","label":"Berinisiatif untuk membantu","kind":"Atd","status":4,"trainee_feedback":null},{"code":"15","label":"Selalu antusias jika diberi tugas","kind":"Atd","status":4,"trainee_feedback":null},{"code":"16","label":"Melaporkan setiap kejadian diluar wewenangnya","kind":"Atd","status":4,"trainee_feedback":null},{"code":"17","label":"Tidak ragu-ragu jika diberi instruksi","kind":"Atd","status":4,"trainee_feedback":null},{"code":"18","label":"Mengoperasikan unit dengan penuh keyakinan","kind":"Atd","status":4,"trainee_feedback":null},{"code":"19","label":"Bersikap proaktif di setiap kegiatan","kind":"Atd","status":4,"trainee_feedback":null},{"code":"20","label":"Tidak malu untuk bertanya jika ada kesulitan","kind":"Atd","status":4,"trainee_feedback":null}]},"excavator":{"groups":[{"title":"Positioning","subtitle":"Cara memposisikan unit, track, dan upper structure di front loading","items":[{"code":"1.1","label":"Cara memposisikan unit di front loading","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.2","label":"Cara membuat landasan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.3","label":"Cara mengatur track dan upper structure","kind":"Skl","trainee_feedback":null,"status":null}]},{"title":"Loading & Dumping","subtitle":"Cara swing, memuat, dan dumping yang aman","items":[{"code":"2.1","label":"Cara swing muatan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.2","label":"Cara swing kosongan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.3","label":"Kombinasi gerakan","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.4","label":"Cara dumping dan kerapihan muatan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.5","label":"Sudut swing","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.6","label":"Cycle time","kind":"Knw","trainee_feedback":null,"status":null}]},{"title":"Digging","subtitle":"Teknik digging dan pengaturan kerja bucket","items":[{"code":"3.1","label":"Teknik digging (urutan pengambilan)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.2","label":"Sudut pengambilan (digging)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.3","label":"Gerakan kombinasi pada saat digging","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.4","label":"Volume bucket","kind":"Knw","trainee_feedback":null,"status":null}]},{"title":"Sloping","subtitle":"Teknik pembuatan slope dan kerapihan permukaan","items":[{"code":"4.1","label":"Teknik pembuatan slope","kind":"Skl","trainee_feedback":null,"status":null},{"code":"4.2","label":"Kerapihan slope","kind":"Skl","trainee_feedback":null,"status":null}]}],"compliance":[{"code":"1","label":"Kesehatan fisik dan perlengkapan\\/ penggunaan APD","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2","label":"Menaiki dan menuruni Unit (Three point contact)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3","label":"Penyetelan tempat duduk","kind":"Skl","trainee_feedback":null,"status":null},{"code":"4","label":"Penggunaan sabuk pengaman\\/safety belt","kind":"Atd","trainee_feedback":null,"status":null},{"code":"5","label":"Penggunaan klakson dan lampu-lampu","kind":"Skl","trainee_feedback":null,"status":null},{"code":"6","label":"Keselamatan saat loading, unloading, positioning, traveling, dan digging","kind":"Skl","trainee_feedback":null,"status":null},{"code":"7","label":"Penyesuaian jenis alat dengan lokasi pekerjaan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"8","label":"Kepedulian terhadap patok-patok survey dan rambu","kind":"Atd","trainee_feedback":null,"status":null},{"code":"9","label":"Parkir unit ditempat yang rata dan aman (pasang lock dan cara meletakkan attachment)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"10","label":"Keselamatan selama operasi","kind":"Knw","trainee_feedback":null,"status":null}],"behavior":[{"code":"1","label":"Mempedulikan pemakaian fuel\\/ bahan bakar","kind":"Atd","trainee_feedback":null,"status":null},{"code":"2","label":"Mempedulikan pemakaian tyre\\/ undercarriage","kind":"Atd","trainee_feedback":null,"status":null},{"code":"3","label":"Mempedulikan akan ketidaknormalan unit","kind":"Atd","trainee_feedback":null,"status":null},{"code":"4","label":"Mempedulikan untuk bekerja dengan efektif dan efisien","kind":"Atd","trainee_feedback":null,"status":null},{"code":"5","label":"Mempedulikan untuk meniadakan pemborosan dimanapun","kind":"Atd","trainee_feedback":null,"status":null},{"code":"6","label":"Melaksanakan aktivitas sesuai instruksi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"7","label":"Berusaha untuk melakukan yang terbaik","kind":"Atd","trainee_feedback":null,"status":null},{"code":"8","label":"Selalu siap menerima tugas yang diberikan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"9","label":"Berani mengingatkan jika ada yang berbuat kesalahan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"10","label":"Disiplin waktu saat pelaksanaan pelatihan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"11","label":"Mematuhi semua aturan yang berlaku","kind":"Atd","trainee_feedback":null,"status":null},{"code":"12","label":"Tidak pernah mangkir","kind":"Atd","trainee_feedback":null,"status":null},{"code":"13","label":"Melaksanakan tugas kelompok bersama-sama","kind":"Atd","trainee_feedback":null,"status":null},{"code":"14","label":"Berinisiatif untuk membantu","kind":"Atd","trainee_feedback":null,"status":null},{"code":"15","label":"Selalu antusias jika diberi tugas","kind":"Atd","trainee_feedback":null,"status":null},{"code":"16","label":"Melaporkan setiap kejadian diluar wewenangnya","kind":"Atd","trainee_feedback":null,"status":null},{"code":"17","label":"Tidak ragu-ragu jika diberi instruksi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"18","label":"Mengoperasikan unit dengan penuh keyakinan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"19","label":"Bersikap proaktif di setiap kegiatan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"20","label":"Tidak malu untuk bertanya jika ada kesulitan","kind":"Atd","trainee_feedback":null,"status":null}]},"dumptruck":{"groups":[{"title":"Loading","subtitle":"Teknik pengambilan haluan, posisi terhadap alat muat, dan penggunaan transmisi\\/brake saat loading","items":[{"code":"1.1","label":"Pengambilan haluan untuk loading\\/ posisi antri\\/","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.2","label":"Posisi terhadap Alat muat (rata, aman & keras)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"1.3","label":"Tranmission \\"N\\" & Penggunaan brake saat loading","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.4","label":"Perhatian saat loading terhadap beban\\/payload meter (Khusus untuk Unit HDT)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"1.5","label":"Perhatian saat loading thd operator alat muat (Khusus untuk Unit LDT)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"1.6","label":"Perhatian terhadap standart muatan (Khusus untuk Unit LDT)","kind":"Knw","trainee_feedback":null,"status":null}]},{"title":"Hauling","subtitle":"Penggunaan speed\\/transmissi, clutch, shift limit, retarder, brake, dan keemihan mengemudi saat bergerak","items":[{"code":"2.1","label":"Penggunaan Speed\\/ Transmissi saat bergerak (Pastikan saat awal muatan harus dari F1 - Khusus HD)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.2","label":"Penggunaan Clutch (Khusus untuk Unit LDT)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.3","label":"Penggunaan Shift Limit & Power\\/ Eco Mode (Khusus untuk Unit HDT)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.4","label":"Penyesuaian tingkat kecepatan, RPM Engine & transmisi dengan kondisi medan (Jalan turunan, mendatar, dan tanjakan)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.5","label":"Penggunaan Retarder waktu turunan (Khusus untuk Unit HDT)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.6","label":"Penggunaan brake di turunan & menghentikan unit (Khusus untuk Unit LDT)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.7","label":"Pengembalian haluan saat membelok\\/ di tikungan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.8","label":"Ketrampilan\\/ kelembutan mengemudi","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.9","label":"Cycle time","kind":"Knw","trainee_feedback":null,"status":null}]},{"title":"Dumping","subtitle":"Teknik pengambilan haluan, posisi dumping, penggunaan brake, dan prosedur dumping\\/vessel","items":[{"code":"3.1","label":"Pengambilan haluan untuk dumping\\/manuver","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.2","label":"Posisi dumping (lokasi harus rata)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"3.3","label":"Penggunaan brake saat Dumping","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.4","label":"Prosedur Dumping (Penggunaan RPM)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"3.5","label":"Prosedur menurunkan Vessel","kind":"Knw","trainee_feedback":null,"status":null},{"code":"3.6","label":"Penempatan material yang tepat di disposal (Khusus untuk Unit HDT)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"3.7","label":"Prosedur menurunkan vesel di hopper\\/ stock pile (Khusus untuk Unit LDT)","kind":"Knw","trainee_feedback":null,"status":null}]}],"compliance":[{"code":"1","label":"Kesehatan fisik dan perlengkapan\\/ penggunaan APD","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2","label":"Menaiki dan menuruni unit (Three point contact)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3","label":"Penyetelan tempat duduk dan steering wheel","kind":"Skl","trainee_feedback":null,"status":null},{"code":"4","label":"Penggunaan sabuk pengaman\\/safety belt","kind":"Atd","trainee_feedback":null,"status":null},{"code":"5","label":"Penggunaan klakson dan lampu-lampu","kind":"Skl","trainee_feedback":null,"status":null},{"code":"6","label":"Keselamatan saat loading\\/harus didalam kabin","kind":"Atd","trainee_feedback":null,"status":null},{"code":"7","label":"Penyesuaian kecepatan terhadap kondisi medan (saat berpapasan, jalan licin, beiringan, kabut dan berdebu)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"8","label":"Kepedulian terhadap Rambu lalu lintas","kind":"Atd","trainee_feedback":null,"status":null},{"code":"9","label":"Keselamatan saat dumping","kind":"Skl","trainee_feedback":null,"status":null},{"code":"10","label":"Sopan santun mengemudi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"11","label":"Parkir unit ditempat yang rata (jarak antara unit dari samping kanan-kiri dan depan-belakang)","kind":"Skl","trainee_feedback":null,"status":null}],"behavior":[{"code":"1","label":"Mempedulikan pemakaian fuel\\/ bahan bakar","kind":"Atd","trainee_feedback":null,"status":null},{"code":"2","label":"Mempedulikan pemakaian tyre\\/ undercarriage","kind":"Atd","trainee_feedback":null,"status":null},{"code":"3","label":"Mempedulikan akan ketidaknormalan unit","kind":"Atd","trainee_feedback":null,"status":null},{"code":"4","label":"Mempedulikan untuk bekerja dengan efektif dan efisien","kind":"Atd","trainee_feedback":null,"status":null},{"code":"5","label":"Mempedulikan untuk meniadakan pemborosan dimanapun","kind":"Atd","trainee_feedback":null,"status":null},{"code":"6","label":"Melaksanakan aktivitas sesuai instruksi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"7","label":"Berusaha untuk melakukan yang terbaik","kind":"Atd","trainee_feedback":null,"status":null},{"code":"8","label":"Selalu siap menerima tugas yang diberikan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"9","label":"Berani mengingatkan jika ada yang berbuat kesalahan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"10","label":"Disiplin waktu saat pelaksanaan pelatihan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"11","label":"Mematuhi semua aturan yang berlaku","kind":"Atd","trainee_feedback":null,"status":null},{"code":"12","label":"Tidak pernah mangkir","kind":"Atd","trainee_feedback":null,"status":null},{"code":"13","label":"Melaksanakan tugas kelompok bersama-sama","kind":"Atd","trainee_feedback":null,"status":null},{"code":"14","label":"Berinisiatif untuk membantu","kind":"Atd","trainee_feedback":null,"status":null},{"code":"15","label":"Selalu antusias jika diberi tugas","kind":"Atd","trainee_feedback":null,"status":null},{"code":"16","label":"Melaporkan setiap kejadian diluar wewenangnya","kind":"Atd","trainee_feedback":null,"status":null},{"code":"17","label":"Tidak ragu-ragu jika diberi instruksi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"18","label":"Mengoperasikan unit dengan penuh keyakinan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"19","label":"Bersikap proaktif di setiap kegiatan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"20","label":"Tidak malu untuk bertanya jika ada kesulitan","kind":"Atd","trainee_feedback":null,"status":null}]},"semidump":{"groups":[{"title":"Loading","subtitle":"Teknik penempatan posisi, posisi trailer terhadap alat muat, dan penggunaan transmisi\\/brake saat loading","items":[{"code":"1.1","label":"Penempatan posisi untuk loading\\/ posisi antri","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.2","label":"Posisi Trailer terhadap Alat muat (rata & aman)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"1.3","label":"Transmission \\"N\\" & Penggunaan Parking Brake saat loading","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.4","label":"Perhatian saat loading terhadap beban\\/ vessel penuh","kind":"Knw","trainee_feedback":null,"status":null}]},{"title":"Hauling","subtitle":"Penggunaan speed\\/transmisi, power\\/offroad mode, trailer brake, dan keemihan mengemudi saat bergerak","items":[{"code":"2.1","label":"Penggunaan Speed\\/ Transmissi saat bergerak (Pastikan saat awal muatan harus dari C low)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.2","label":"Penggunaan Power\\/ Offroad Mode","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.3","label":"Penyesuaian tingkat kecepatan dengan kondisi medan (jalan turunan, mendatar, dan tanjakan)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.4","label":"Penggunaan Trailer Brake","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.5","label":"Pengembalian haluan saat membelok\\/ditikungan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.6","label":"Ketrampilan\\/kelembutan mengemudi","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.7","label":"Cycle time","kind":"Skl","trainee_feedback":null,"status":null}]},{"title":"Dumping","subtitle":"Teknik pengambilan haluan, posisi dumping, penggunaan brake, dan prosedur dumping\\/vessel","items":[{"code":"3.1","label":"Pengambilan haluan untuk dumping\\/ manuver","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.2","label":"Posisi dumping (lokasi harus rata)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"3.3","label":"Penggunaan brake saat Dumping","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.4","label":"Prosedur Dumping (Penggunaan RPM)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"3.5","label":"Prosedur menurunkan Vessel","kind":"Knw","trainee_feedback":null,"status":null},{"code":"3.6","label":"Penempatan material yang tepat di Hopper\\/Stock Pile","kind":"Knw","trainee_feedback":null,"status":null}]}],"compliance":[{"code":"1","label":"Kesehatan fisik dan perlengkapan\\/ penggunaan APD","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2","label":"Menaiki dan menuruni unit (three point contact)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3","label":"Penyetelan tempat duduk dan steering wheel","kind":"Skl","trainee_feedback":null,"status":null},{"code":"4","label":"Penggunaan sabuk pengaman\\/ safety belt","kind":"Atd","trainee_feedback":null,"status":null},{"code":"5","label":"Penggunaan klakson dan lampu-lampu","kind":"Skl","trainee_feedback":null,"status":null},{"code":"6","label":"Keselamatan saat loading\\/ harus didalam kabin","kind":"Atd","trainee_feedback":null,"status":null},{"code":"7","label":"Penyesuaian kecepatan terhadap kondisi medan (saat berpapasan, jalan licin, beiringan, kabut dan berdebu)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"8","label":"Kepedulian terhadap rambu lalu lintas","kind":"Atd","trainee_feedback":null,"status":null},{"code":"9","label":"Keselamatan saat dumping","kind":"Skl","trainee_feedback":null,"status":null},{"code":"10","label":"Sopan santun mengemudi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"11","label":"Parkir unit ditempat yang rata (jarak antara unit dari samping kanan-kiri dan depan-belakang)","kind":"Skl","trainee_feedback":null,"status":null}],"behavior":[{"code":"1","label":"Mempedulikan pemakaian fuel\\/ bahan bakar","kind":"Atd","trainee_feedback":null,"status":null},{"code":"2","label":"Mempedulikan pemakaian tyre\\/ undercarriage","kind":"Atd","trainee_feedback":null,"status":null},{"code":"3","label":"Mempedulikan akan ketidaknormalan unit","kind":"Atd","trainee_feedback":null,"status":null},{"code":"4","label":"Mempedulikan untuk bekerja dengan efektif dan efisien","kind":"Atd","trainee_feedback":null,"status":null},{"code":"5","label":"Mempedulikan untuk meniadakan pemborosan dimanapun","kind":"Atd","trainee_feedback":null,"status":null},{"code":"6","label":"Melaksanakan aktivitas sesuai instruksi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"7","label":"Berusaha untuk melakukan yang terbaik","kind":"Atd","trainee_feedback":null,"status":null},{"code":"8","label":"Selalu siap menerima tugas yang diberikan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"9","label":"Berani mengingatkan jika ada yang berbuat kesalahan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"10","label":"Disiplin waktu saat pelaksanaan pelatihan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"11","label":"Mematuhi semua aturan yang berlaku","kind":"Atd","trainee_feedback":null,"status":null},{"code":"12","label":"Tidak pernah mangkir","kind":"Atd","trainee_feedback":null,"status":null},{"code":"13","label":"Melaksanakan tugas kelompok bersama-sama","kind":"Atd","trainee_feedback":null,"status":null},{"code":"14","label":"Berinisiatif untuk membantu","kind":"Atd","trainee_feedback":null,"status":null},{"code":"15","label":"Selalu antusias jika diberi tugas","kind":"Atd","trainee_feedback":null,"status":null},{"code":"16","label":"Melaporkan setiap kejadian diluar wewenangnya","kind":"Atd","trainee_feedback":null,"status":null},{"code":"17","label":"Tidak ragu-ragu jika diberi instruksi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"18","label":"Mengoperasikan unit dengan penuh keyakinan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"19","label":"Bersikap proaktif di setiap kegiatan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"20","label":"Tidak malu untuk bertanya jika ada kesulitan","kind":"Atd","trainee_feedback":null,"status":null}]},"wheelloader":{"groups":[{"title":"Traveling","subtitle":"Pengoperasian wheel loader saat bergerak: speed, manuver, dan pemilihan jalur","items":[{"code":"1.1","label":"Memposisikan attachment dengan benar","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.2","label":"Penyesuaian speed dengan kondisi medan","kind":"Skl","trainee_feedback":null,"status":null}]}]}}	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
36	LOG-202609-0052	16	\N	\N	\N	2026-09-02	day	BMO 1	\N	\N	1402.0	1410.0	8.0	final_approved	\N	\N	\N	\N	2026-09-10 10:40:29	2026-09-10 10:40:29	{"meta":{"unit_type":"DZ"},"track":{"groups":[{"items":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]},{"items":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":null,"trainee_feedback":"N\\/A"},{"status":4,"trainee_feedback":null}]},{"items":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]},{"items":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]}],"compliance":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}],"behavior":[{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null},{"status":4,"trainee_feedback":null}]},"excavator":{"groups":[{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]}],"compliance":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}],"behavior":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},"dumptruck":{"groups":[{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]}],"compliance":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}],"behavior":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},"semidump":{"groups":[{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]}],"compliance":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}],"behavior":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]},"wheelloader":{"groups":[{"items":[{"status":null,"trainee_feedback":null},{"status":null,"trainee_feedback":null}]}]}}	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
37	LOG-202609-0053	16	\N	\N	\N	2026-09-03	day	BMO 1	\N	\N	1410.0	1418.0	8.0	final_approved	\N	\N	\N	\N	2026-09-10 10:40:29	2026-09-10 10:40:29	{"meta":{"category_code":"DZ","unit_family":"track","trainee_name":"Henky Udiansyah","certification":"Green","company":"PT Maju Mundur","sticker_expired_at":"2026-12-31","assessment_mode":"pendampingan","assessment_stage_detail":null,"unit_type":"DZ"},"track":{"groups":[{"title":"Dozing & Digging untuk Unit (DZ) \\/ Grading & Digging untuk Unit (GR)","subtitle":"Cara memosisisikan blade, menggali, dan mendorong material","items":[{"code":"1.1","label":"Cara memposisikan Blade pada saat mendorong\\/ grading","kind":"Skl","status":4,"trainee_feedback":null},{"code":"1.2","label":"Penggunaan Tilt Blade","kind":"Knw","status":4,"trainee_feedback":null},{"code":"1.3","label":"Cara Pengoperasian blade untuk mendorong\\/ ditching","kind":"Skl","status":4,"trainee_feedback":null},{"code":"1.4","label":"Cara Pengoperasian blade untuk menggali\\/sloping","kind":"Skl","status":4,"trainee_feedback":null},{"code":"1.5","label":"Penyesuaian beban dengan rpm\\/posisi transmissi","kind":"Skl","status":4,"trainee_feedback":null},{"code":"1.6","label":"Teknik dozing\\/grading\\/digging","kind":"Skl","status":4,"trainee_feedback":null}]},{"title":"Spreading & Leveling","subtitle":"Pengoperasian untuk meratakan, memadatkan, dan membentuk area kerja","items":[{"code":"2.1","label":"Penggunaan Speed\\/ Transmissi saat bergerak","kind":"Knw","status":4,"trainee_feedback":null},{"code":"2.2","label":"Cara leveling menggunakan tilt","kind":"Knw","status":4,"trainee_feedback":null},{"code":"2.3","label":"Cara menghampar material untuk membuat jalan, menimbun lubang dll","kind":"Skl","status":4,"trainee_feedback":null},{"code":"2.4","label":"Filling pada saat melevelkan area kerja","kind":"Skl","status":4,"trainee_feedback":null},{"code":"2.5","label":"Penggunaan Steering","kind":"Skl","status":4,"trainee_feedback":null},{"code":"2.6","label":"Penggunaan Articulated (Khusus untuk unit GR)","kind":"Skl","status":4,"trainee_feedback":null},{"code":"2.7","label":"Teknik spreading\\/levelling","kind":"Skl","status":4,"trainee_feedback":null}]},{"title":"Ripping","subtitle":"Khusus untuk pekerjaan ripping dan pembukaan material keras","items":[{"code":"3.1","label":"Cara memposisikan Ripper","kind":"Skl","status":4,"trainee_feedback":null},{"code":"3.2","label":"Teknik Penetrasi Ripping","kind":"Knw","status":4,"trainee_feedback":null},{"code":"3.3","label":"Penyesuaian posisi ripper dengan kekerasan material","kind":"Skl","status":4,"trainee_feedback":null}]},{"title":"Finishing","subtitle":"Finishing grading dan koreksi permukaan kerja","items":[{"code":"4.1","label":"Kesesuaian penggunaan speed","kind":"Skl","status":4,"trainee_feedback":null},{"code":"4.2","label":"Hasil akhir pendorongan (hasil pekerjaan)","kind":"Skl","status":4,"trainee_feedback":null}]}],"compliance":[{"code":"1","label":"Kesehatan fisik dan perlengkapan\\/penggunaan APD","kind":"Knw","status":4,"trainee_feedback":null},{"code":"2","label":"Menaiki dan menuruni Unit (Three point contact)","kind":"Skl","status":4,"trainee_feedback":null},{"code":"3","label":"Penyetelan tempat duduk","kind":"Skl","status":4,"trainee_feedback":null},{"code":"4","label":"Penggunaan sabuk pengaman\\/ safety belt","kind":"Atd","status":4,"trainee_feedback":null},{"code":"5","label":"Penggunaan klakson dan lampu-lampu","kind":"Skl","status":4,"trainee_feedback":null},{"code":"6","label":"Keselamatan saat digging, dozing, spreading, levelling, ripping, travelling","kind":"Skl","status":4,"trainee_feedback":null},{"code":"7","label":"Penyesuaian jenis alat dengan lokasi pekerjaan","kind":"Skl","status":4,"trainee_feedback":null},{"code":"8","label":"Kepedulian terhadap patok-patok survey dan rambu","kind":"Atd","status":4,"trainee_feedback":null},{"code":"9","label":"Parkir unit ditempat yang rata dan aman (pasang lock dan cara meletakkan attachment)","kind":"Skl","status":4,"trainee_feedback":null},{"code":"10","label":"Keselamatan selama operasi","kind":"Knw","status":4,"trainee_feedback":null}],"behavior":[{"code":"1","label":"Mempedulikan pemakaian fuel\\/ bahan bakar","kind":"Atd","status":4,"trainee_feedback":null},{"code":"2","label":"Mempedulikan pemakaian tyre\\/ undercarriage","kind":"Atd","status":4,"trainee_feedback":null},{"code":"3","label":"Mempedulikan akan ketidaknormalan unit","kind":"Atd","status":4,"trainee_feedback":null},{"code":"4","label":"Mempedulikan untuk bekerja dengan efektif dan efisien","kind":"Atd","status":4,"trainee_feedback":null},{"code":"5","label":"Mempedulikan untuk meniadakan pemborosan dimanapun","kind":"Atd","status":4,"trainee_feedback":null},{"code":"6","label":"Melaksanakan aktivitas sesuai instruksi","kind":"Atd","status":4,"trainee_feedback":null},{"code":"7","label":"Berusaha untuk melakukan yang terbaik","kind":"Atd","status":4,"trainee_feedback":null},{"code":"8","label":"Selalu siap menerima tugas yang diberikan","kind":"Atd","status":4,"trainee_feedback":null},{"code":"9","label":"Berani mengingatkan jika ada yang berbuat kesalahan","kind":"Atd","status":4,"trainee_feedback":null},{"code":"10","label":"Disiplin waktu saat pelaksanaan pelatihan","kind":"Atd","status":4,"trainee_feedback":null},{"code":"11","label":"Mematuhi semua aturan yang berlaku","kind":"Atd","status":4,"trainee_feedback":null},{"code":"12","label":"Tidak pernah mangkir","kind":"Atd","status":4,"trainee_feedback":null},{"code":"13","label":"Melaksanakan tugas kelompok bersama-sama","kind":"Atd","status":4,"trainee_feedback":null},{"code":"14","label":"Berinisiatif untuk membantu","kind":"Atd","status":4,"trainee_feedback":null},{"code":"15","label":"Selalu antusias jika diberi tugas","kind":"Atd","status":4,"trainee_feedback":null},{"code":"16","label":"Melaporkan setiap kejadian diluar wewenangnya","kind":"Atd","status":4,"trainee_feedback":null},{"code":"17","label":"Tidak ragu-ragu jika diberi instruksi","kind":"Atd","status":4,"trainee_feedback":null},{"code":"18","label":"Mengoperasikan unit dengan penuh keyakinan","kind":"Atd","status":4,"trainee_feedback":null},{"code":"19","label":"Bersikap proaktif di setiap kegiatan","kind":"Atd","status":4,"trainee_feedback":null},{"code":"20","label":"Tidak malu untuk bertanya jika ada kesulitan","kind":"Atd","status":4,"trainee_feedback":null}]},"excavator":{"groups":[{"title":"Positioning","subtitle":"Cara memposisikan unit, track, dan upper structure di front loading","items":[{"code":"1.1","label":"Cara memposisikan unit di front loading","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.2","label":"Cara membuat landasan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.3","label":"Cara mengatur track dan upper structure","kind":"Skl","trainee_feedback":null,"status":null}]},{"title":"Loading & Dumping","subtitle":"Cara swing, memuat, dan dumping yang aman","items":[{"code":"2.1","label":"Cara swing muatan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.2","label":"Cara swing kosongan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.3","label":"Kombinasi gerakan","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.4","label":"Cara dumping dan kerapihan muatan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.5","label":"Sudut swing","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.6","label":"Cycle time","kind":"Knw","trainee_feedback":null,"status":null}]},{"title":"Digging","subtitle":"Teknik digging dan pengaturan kerja bucket","items":[{"code":"3.1","label":"Teknik digging (urutan pengambilan)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.2","label":"Sudut pengambilan (digging)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.3","label":"Gerakan kombinasi pada saat digging","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.4","label":"Volume bucket","kind":"Knw","trainee_feedback":null,"status":null}]},{"title":"Sloping","subtitle":"Teknik pembuatan slope dan kerapihan permukaan","items":[{"code":"4.1","label":"Teknik pembuatan slope","kind":"Skl","trainee_feedback":null,"status":null},{"code":"4.2","label":"Kerapihan slope","kind":"Skl","trainee_feedback":null,"status":null}]}],"compliance":[{"code":"1","label":"Kesehatan fisik dan perlengkapan\\/ penggunaan APD","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2","label":"Menaiki dan menuruni Unit (Three point contact)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3","label":"Penyetelan tempat duduk","kind":"Skl","trainee_feedback":null,"status":null},{"code":"4","label":"Penggunaan sabuk pengaman\\/safety belt","kind":"Atd","trainee_feedback":null,"status":null},{"code":"5","label":"Penggunaan klakson dan lampu-lampu","kind":"Skl","trainee_feedback":null,"status":null},{"code":"6","label":"Keselamatan saat loading, unloading, positioning, traveling, dan digging","kind":"Skl","trainee_feedback":null,"status":null},{"code":"7","label":"Penyesuaian jenis alat dengan lokasi pekerjaan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"8","label":"Kepedulian terhadap patok-patok survey dan rambu","kind":"Atd","trainee_feedback":null,"status":null},{"code":"9","label":"Parkir unit ditempat yang rata dan aman (pasang lock dan cara meletakkan attachment)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"10","label":"Keselamatan selama operasi","kind":"Knw","trainee_feedback":null,"status":null}],"behavior":[{"code":"1","label":"Mempedulikan pemakaian fuel\\/ bahan bakar","kind":"Atd","trainee_feedback":null,"status":null},{"code":"2","label":"Mempedulikan pemakaian tyre\\/ undercarriage","kind":"Atd","trainee_feedback":null,"status":null},{"code":"3","label":"Mempedulikan akan ketidaknormalan unit","kind":"Atd","trainee_feedback":null,"status":null},{"code":"4","label":"Mempedulikan untuk bekerja dengan efektif dan efisien","kind":"Atd","trainee_feedback":null,"status":null},{"code":"5","label":"Mempedulikan untuk meniadakan pemborosan dimanapun","kind":"Atd","trainee_feedback":null,"status":null},{"code":"6","label":"Melaksanakan aktivitas sesuai instruksi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"7","label":"Berusaha untuk melakukan yang terbaik","kind":"Atd","trainee_feedback":null,"status":null},{"code":"8","label":"Selalu siap menerima tugas yang diberikan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"9","label":"Berani mengingatkan jika ada yang berbuat kesalahan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"10","label":"Disiplin waktu saat pelaksanaan pelatihan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"11","label":"Mematuhi semua aturan yang berlaku","kind":"Atd","trainee_feedback":null,"status":null},{"code":"12","label":"Tidak pernah mangkir","kind":"Atd","trainee_feedback":null,"status":null},{"code":"13","label":"Melaksanakan tugas kelompok bersama-sama","kind":"Atd","trainee_feedback":null,"status":null},{"code":"14","label":"Berinisiatif untuk membantu","kind":"Atd","trainee_feedback":null,"status":null},{"code":"15","label":"Selalu antusias jika diberi tugas","kind":"Atd","trainee_feedback":null,"status":null},{"code":"16","label":"Melaporkan setiap kejadian diluar wewenangnya","kind":"Atd","trainee_feedback":null,"status":null},{"code":"17","label":"Tidak ragu-ragu jika diberi instruksi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"18","label":"Mengoperasikan unit dengan penuh keyakinan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"19","label":"Bersikap proaktif di setiap kegiatan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"20","label":"Tidak malu untuk bertanya jika ada kesulitan","kind":"Atd","trainee_feedback":null,"status":null}]},"dumptruck":{"groups":[{"title":"Loading","subtitle":"Teknik pengambilan haluan, posisi terhadap alat muat, dan penggunaan transmisi\\/brake saat loading","items":[{"code":"1.1","label":"Pengambilan haluan untuk loading\\/ posisi antri\\/","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.2","label":"Posisi terhadap Alat muat (rata, aman & keras)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"1.3","label":"Tranmission \\"N\\" & Penggunaan brake saat loading","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.4","label":"Perhatian saat loading terhadap beban\\/payload meter (Khusus untuk Unit HDT)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"1.5","label":"Perhatian saat loading thd operator alat muat (Khusus untuk Unit LDT)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"1.6","label":"Perhatian terhadap standart muatan (Khusus untuk Unit LDT)","kind":"Knw","trainee_feedback":null,"status":null}]},{"title":"Hauling","subtitle":"Penggunaan speed\\/transmissi, clutch, shift limit, retarder, brake, dan keemihan mengemudi saat bergerak","items":[{"code":"2.1","label":"Penggunaan Speed\\/ Transmissi saat bergerak (Pastikan saat awal muatan harus dari F1 - Khusus HD)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.2","label":"Penggunaan Clutch (Khusus untuk Unit LDT)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.3","label":"Penggunaan Shift Limit & Power\\/ Eco Mode (Khusus untuk Unit HDT)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.4","label":"Penyesuaian tingkat kecepatan, RPM Engine & transmisi dengan kondisi medan (Jalan turunan, mendatar, dan tanjakan)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.5","label":"Penggunaan Retarder waktu turunan (Khusus untuk Unit HDT)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.6","label":"Penggunaan brake di turunan & menghentikan unit (Khusus untuk Unit LDT)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.7","label":"Pengembalian haluan saat membelok\\/ di tikungan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.8","label":"Ketrampilan\\/ kelembutan mengemudi","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.9","label":"Cycle time","kind":"Knw","trainee_feedback":null,"status":null}]},{"title":"Dumping","subtitle":"Teknik pengambilan haluan, posisi dumping, penggunaan brake, dan prosedur dumping\\/vessel","items":[{"code":"3.1","label":"Pengambilan haluan untuk dumping\\/manuver","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.2","label":"Posisi dumping (lokasi harus rata)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"3.3","label":"Penggunaan brake saat Dumping","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.4","label":"Prosedur Dumping (Penggunaan RPM)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"3.5","label":"Prosedur menurunkan Vessel","kind":"Knw","trainee_feedback":null,"status":null},{"code":"3.6","label":"Penempatan material yang tepat di disposal (Khusus untuk Unit HDT)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"3.7","label":"Prosedur menurunkan vesel di hopper\\/ stock pile (Khusus untuk Unit LDT)","kind":"Knw","trainee_feedback":null,"status":null}]}],"compliance":[{"code":"1","label":"Kesehatan fisik dan perlengkapan\\/ penggunaan APD","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2","label":"Menaiki dan menuruni unit (Three point contact)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3","label":"Penyetelan tempat duduk dan steering wheel","kind":"Skl","trainee_feedback":null,"status":null},{"code":"4","label":"Penggunaan sabuk pengaman\\/safety belt","kind":"Atd","trainee_feedback":null,"status":null},{"code":"5","label":"Penggunaan klakson dan lampu-lampu","kind":"Skl","trainee_feedback":null,"status":null},{"code":"6","label":"Keselamatan saat loading\\/harus didalam kabin","kind":"Atd","trainee_feedback":null,"status":null},{"code":"7","label":"Penyesuaian kecepatan terhadap kondisi medan (saat berpapasan, jalan licin, beiringan, kabut dan berdebu)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"8","label":"Kepedulian terhadap Rambu lalu lintas","kind":"Atd","trainee_feedback":null,"status":null},{"code":"9","label":"Keselamatan saat dumping","kind":"Skl","trainee_feedback":null,"status":null},{"code":"10","label":"Sopan santun mengemudi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"11","label":"Parkir unit ditempat yang rata (jarak antara unit dari samping kanan-kiri dan depan-belakang)","kind":"Skl","trainee_feedback":null,"status":null}],"behavior":[{"code":"1","label":"Mempedulikan pemakaian fuel\\/ bahan bakar","kind":"Atd","trainee_feedback":null,"status":null},{"code":"2","label":"Mempedulikan pemakaian tyre\\/ undercarriage","kind":"Atd","trainee_feedback":null,"status":null},{"code":"3","label":"Mempedulikan akan ketidaknormalan unit","kind":"Atd","trainee_feedback":null,"status":null},{"code":"4","label":"Mempedulikan untuk bekerja dengan efektif dan efisien","kind":"Atd","trainee_feedback":null,"status":null},{"code":"5","label":"Mempedulikan untuk meniadakan pemborosan dimanapun","kind":"Atd","trainee_feedback":null,"status":null},{"code":"6","label":"Melaksanakan aktivitas sesuai instruksi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"7","label":"Berusaha untuk melakukan yang terbaik","kind":"Atd","trainee_feedback":null,"status":null},{"code":"8","label":"Selalu siap menerima tugas yang diberikan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"9","label":"Berani mengingatkan jika ada yang berbuat kesalahan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"10","label":"Disiplin waktu saat pelaksanaan pelatihan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"11","label":"Mematuhi semua aturan yang berlaku","kind":"Atd","trainee_feedback":null,"status":null},{"code":"12","label":"Tidak pernah mangkir","kind":"Atd","trainee_feedback":null,"status":null},{"code":"13","label":"Melaksanakan tugas kelompok bersama-sama","kind":"Atd","trainee_feedback":null,"status":null},{"code":"14","label":"Berinisiatif untuk membantu","kind":"Atd","trainee_feedback":null,"status":null},{"code":"15","label":"Selalu antusias jika diberi tugas","kind":"Atd","trainee_feedback":null,"status":null},{"code":"16","label":"Melaporkan setiap kejadian diluar wewenangnya","kind":"Atd","trainee_feedback":null,"status":null},{"code":"17","label":"Tidak ragu-ragu jika diberi instruksi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"18","label":"Mengoperasikan unit dengan penuh keyakinan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"19","label":"Bersikap proaktif di setiap kegiatan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"20","label":"Tidak malu untuk bertanya jika ada kesulitan","kind":"Atd","trainee_feedback":null,"status":null}]},"semidump":{"groups":[{"title":"Loading","subtitle":"Teknik penempatan posisi, posisi trailer terhadap alat muat, dan penggunaan transmisi\\/brake saat loading","items":[{"code":"1.1","label":"Penempatan posisi untuk loading\\/ posisi antri","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.2","label":"Posisi Trailer terhadap Alat muat (rata & aman)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"1.3","label":"Transmission \\"N\\" & Penggunaan Parking Brake saat loading","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.4","label":"Perhatian saat loading terhadap beban\\/ vessel penuh","kind":"Knw","trainee_feedback":null,"status":null}]},{"title":"Hauling","subtitle":"Penggunaan speed\\/transmisi, power\\/offroad mode, trailer brake, dan keemihan mengemudi saat bergerak","items":[{"code":"2.1","label":"Penggunaan Speed\\/ Transmissi saat bergerak (Pastikan saat awal muatan harus dari C low)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.2","label":"Penggunaan Power\\/ Offroad Mode","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2.3","label":"Penyesuaian tingkat kecepatan dengan kondisi medan (jalan turunan, mendatar, dan tanjakan)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.4","label":"Penggunaan Trailer Brake","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.5","label":"Pengembalian haluan saat membelok\\/ditikungan","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.6","label":"Ketrampilan\\/kelembutan mengemudi","kind":"Skl","trainee_feedback":null,"status":null},{"code":"2.7","label":"Cycle time","kind":"Skl","trainee_feedback":null,"status":null}]},{"title":"Dumping","subtitle":"Teknik pengambilan haluan, posisi dumping, penggunaan brake, dan prosedur dumping\\/vessel","items":[{"code":"3.1","label":"Pengambilan haluan untuk dumping\\/ manuver","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.2","label":"Posisi dumping (lokasi harus rata)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"3.3","label":"Penggunaan brake saat Dumping","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3.4","label":"Prosedur Dumping (Penggunaan RPM)","kind":"Knw","trainee_feedback":null,"status":null},{"code":"3.5","label":"Prosedur menurunkan Vessel","kind":"Knw","trainee_feedback":null,"status":null},{"code":"3.6","label":"Penempatan material yang tepat di Hopper\\/Stock Pile","kind":"Knw","trainee_feedback":null,"status":null}]}],"compliance":[{"code":"1","label":"Kesehatan fisik dan perlengkapan\\/ penggunaan APD","kind":"Knw","trainee_feedback":null,"status":null},{"code":"2","label":"Menaiki dan menuruni unit (three point contact)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"3","label":"Penyetelan tempat duduk dan steering wheel","kind":"Skl","trainee_feedback":null,"status":null},{"code":"4","label":"Penggunaan sabuk pengaman\\/ safety belt","kind":"Atd","trainee_feedback":null,"status":null},{"code":"5","label":"Penggunaan klakson dan lampu-lampu","kind":"Skl","trainee_feedback":null,"status":null},{"code":"6","label":"Keselamatan saat loading\\/ harus didalam kabin","kind":"Atd","trainee_feedback":null,"status":null},{"code":"7","label":"Penyesuaian kecepatan terhadap kondisi medan (saat berpapasan, jalan licin, beiringan, kabut dan berdebu)","kind":"Skl","trainee_feedback":null,"status":null},{"code":"8","label":"Kepedulian terhadap rambu lalu lintas","kind":"Atd","trainee_feedback":null,"status":null},{"code":"9","label":"Keselamatan saat dumping","kind":"Skl","trainee_feedback":null,"status":null},{"code":"10","label":"Sopan santun mengemudi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"11","label":"Parkir unit ditempat yang rata (jarak antara unit dari samping kanan-kiri dan depan-belakang)","kind":"Skl","trainee_feedback":null,"status":null}],"behavior":[{"code":"1","label":"Mempedulikan pemakaian fuel\\/ bahan bakar","kind":"Atd","trainee_feedback":null,"status":null},{"code":"2","label":"Mempedulikan pemakaian tyre\\/ undercarriage","kind":"Atd","trainee_feedback":null,"status":null},{"code":"3","label":"Mempedulikan akan ketidaknormalan unit","kind":"Atd","trainee_feedback":null,"status":null},{"code":"4","label":"Mempedulikan untuk bekerja dengan efektif dan efisien","kind":"Atd","trainee_feedback":null,"status":null},{"code":"5","label":"Mempedulikan untuk meniadakan pemborosan dimanapun","kind":"Atd","trainee_feedback":null,"status":null},{"code":"6","label":"Melaksanakan aktivitas sesuai instruksi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"7","label":"Berusaha untuk melakukan yang terbaik","kind":"Atd","trainee_feedback":null,"status":null},{"code":"8","label":"Selalu siap menerima tugas yang diberikan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"9","label":"Berani mengingatkan jika ada yang berbuat kesalahan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"10","label":"Disiplin waktu saat pelaksanaan pelatihan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"11","label":"Mematuhi semua aturan yang berlaku","kind":"Atd","trainee_feedback":null,"status":null},{"code":"12","label":"Tidak pernah mangkir","kind":"Atd","trainee_feedback":null,"status":null},{"code":"13","label":"Melaksanakan tugas kelompok bersama-sama","kind":"Atd","trainee_feedback":null,"status":null},{"code":"14","label":"Berinisiatif untuk membantu","kind":"Atd","trainee_feedback":null,"status":null},{"code":"15","label":"Selalu antusias jika diberi tugas","kind":"Atd","trainee_feedback":null,"status":null},{"code":"16","label":"Melaporkan setiap kejadian diluar wewenangnya","kind":"Atd","trainee_feedback":null,"status":null},{"code":"17","label":"Tidak ragu-ragu jika diberi instruksi","kind":"Atd","trainee_feedback":null,"status":null},{"code":"18","label":"Mengoperasikan unit dengan penuh keyakinan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"19","label":"Bersikap proaktif di setiap kegiatan","kind":"Atd","trainee_feedback":null,"status":null},{"code":"20","label":"Tidak malu untuk bertanya jika ada kesulitan","kind":"Atd","trainee_feedback":null,"status":null}]},"wheelloader":{"groups":[{"title":"Traveling","subtitle":"Pengoperasian wheel loader saat bergerak: speed, manuver, dan pemilihan jalur","items":[{"code":"1.1","label":"Memposisikan attachment dengan benar","kind":"Skl","trainee_feedback":null,"status":null},{"code":"1.2","label":"Penyesuaian speed dengan kondisi medan","kind":"Skl","trainee_feedback":null,"status":null}]}]}}	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N
\.


--
-- TOC entry 5220 (class 0 OID 17012)
-- Dependencies: 227
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- TOC entry 5221 (class 0 OID 17021)
-- Dependencies: 228
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
R9dVaDrkmwRCi8gM0nGGh9eoi81bKkYkQt5FnPxP	\N	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36	YTozOntzOjY6Il90b2tlbiI7czo0MDoidERYNjhicE5NRHRCTkZMZHpnUjJQTmFYQmNKbHBDQXJqYXI2cVdUWCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=	1789009503
BHGNOTngPEX4VgqL52bHHkfRtP5xGJWiJvOSQ0jH	\N	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36	YTozOntzOjY6Il90b2tlbiI7czo0MDoiSHV2MUM0NFR1TDU5NTFXVW44aFNRdDVhM3E2Q2dXS2RsdVlmeUxXNSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=	1789524960
38FAtilcSns22jDC7NrQBwD6eygiUlCsfI4p9Cl5	\N	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.138.0 Chrome/148.0.7778.280 Electron/42.10.0 Safari/537.36	YTozOntzOjY6Il90b2tlbiI7czo0MDoiNWN6ZDE5N3BMVEZsY20zUjkxZzc5eUpOOGJQOVQ2U2NpMExqV3pqTSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=	1790064536
BHL4YGlqlvIjD0n6kxjRM0hazaWj1JeXbGqsGlHP	4	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36	YTo0OntzOjY6Il90b2tlbiI7czo0MDoiN2w5aUIxRlFNcGxwbXJGbk1JWW81QllvMnFFanVGOVA5M21QTFgyRiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC90cmFpbmluZy1jZW50cmUvdXNlcnMvMTYvZWRpdCI7czo1OiJyb3V0ZSI7czoyNjoidHJhaW5pbmctY2VudHJlLnVzZXJzLmVkaXQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo0O30=	1790065315
\.


--
-- TOC entry 5239 (class 0 OID 17337)
-- Dependencies: 246
-- Data for Name: trainee_phase_histories; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.trainee_phase_histories (id, user_id, from_phase, to_phase, evaluation_id, approved_by, notes, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 5233 (class 0 OID 17217)
-- Dependencies: 240
-- Data for Name: trainee_trainer; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.trainee_trainer (id, trainee_id, trainer_id, trainer_type, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 5219 (class 0 OID 16987)
-- Dependencies: 226
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, sid, name, email, password, phone, avatar, remember_token, created_at, updated_at, signature_path, must_change_password, is_super_admin, trainer_type, certification, company, equipment_category_id, equipment_number, current_phase, role, initial_hm_day, initial_hm_night) FROM stdin;
6	11111	Muhammad Hilmy Mahardika	\N	$2y$12$AYBi5v39138xTL84Fq497upWzdtw4rtBvV1pCzPDilOStYMHCpStG	\N	\N	\N	2026-09-10 10:33:23	2026-09-10 10:33:23	\N	t	f	instruktur	\N	\N	\N	\N	\N	trainer	0.0	0.0
8	22222	Maharaka	raka@beraucoal.co.id	$2y$12$BJ0W3w42rjfUVXaZ1SgwCeqrPINhNz73kaWQHGK7r9IhVz9kKEGdK	081122334455	\N	\N	2026-09-10 10:38:01	2026-09-10 10:38:01	signatures/JF32U83QPBI7Rc7USgtpUlag16EjcSVXKhis07mv.png	t	f	pengawas	Green	\N	\N	\N	evaluasi_3	trainer	0.0	0.0
9	33333	Samih	samih@beraucoal.co.id	$2y$12$IzC4hSFfQWXCTaHjCg/TzOyAxRA8qKl5lGGZj9MyUbXcqsukTZq1m	081122334455	\N	\N	2026-09-10 10:38:01	2026-09-10 10:38:01	signatures/gHzAiIaeEXMgQf0LSx1EcvV1RTxXjEvYiMVAf93m.png	t	f	pengawas	Green	\N	\N	\N	evaluasi_3	trainer	0.0	0.0
10	44444	Nugroho	nugroho@beraucoal.co.id	$2y$12$iXRXVT9ZnLY16VpT/2CjUOJ8EVUyUi67/R43rGoDlcpiJh/osXpsu	081122334455	\N	\N	2026-09-10 10:38:01	2026-09-10 10:38:01	signatures/fJ5K4QGB495rOcinGDbQWHmSAZ2HyQo5MGRQ1Oxp.jpg	t	f	operator_pendamping	Green	\N	\N	\N	evaluasi_3	trainer	0.0	0.0
11	66666	Omar	omar@beraucoal.co.id	$2y$12$2ZpCj658ogc9DwNUWp4AwOoMVs6k7QQYahlulkb1vE79dbegKBdCu	081122334455	\N	\N	2026-09-10 10:38:01	2026-09-10 10:38:01	signatures/78g0KwrjBuMxUwNLhppezLDpUaKU6PsBrbUficW3.png	t	f	operator_pendamping	Green	\N	\N	\N	evaluasi_3	trainer	0.0	0.0
13	PJO11	PJO MTL	pjo1@mtl.co.id	$2y$12$YPeg3Y673Pg54SS7WPF.pOAYuZsOJUTjh4X6CtAmNGPCxoFh.VyTu	081122334455	\N	\N	2026-09-10 10:38:02	2026-09-10 10:38:02	signatures/ROMTBgofpPDsxgB7OmwKhYoHskw9I3dMHPhEzLoM.jpg	t	f	\N	Green	\N	\N	\N	evaluasi_3	pjo	0.0	0.0
14	HSE111	HSE CT	hse1@bc.co.id	$2y$12$JOgA5DKecy5pRhsvFON5oOE6TizdmeLrXlLslAC7uNX6WlbBE1JWO	081122334455	\N	\N	2026-09-10 10:38:02	2026-09-10 10:38:02	signatures/4Pt0rchCUEkaLji7EaH1UVtpSz0qfJRhd2o5Wh5t.png	t	f	\N	Green	\N	\N	\N	evaluasi_3	hse_ct	0.0	0.0
7	5	Admin TC 1	admintc1@beraucoal.co.id	$2y$12$dUZRKvVfQHLyL2MQg3LgsutC7/e76agw3G0ZHCDa.TqPo/SJIjlK2	081122334455	\N	\N	2026-09-10 10:38:00	2026-09-10 10:55:02	signatures/Z8cdFGB3Shr5Sgsy5W8R5Qg2Pea2lxvZjfe79i92.jpg	f	f	\N	Green	\N	\N	\N	evaluasi_3	admin	0.0	0.0
4	99999	Bakir	training.centre@beraucoal.co.id	$2y$12$ajOMJqFCHvZSKCI.K7oeou.lWfgWOuOdXfsEPPHMOU1.T9GsW/dLq	+62 812-1000-2000	\N	\N	2026-09-10 10:22:33	2026-09-10 10:57:44	\N	f	f	\N	\N	\N	\N	\N	\N	admin	0.0	0.0
16	5HSJM	Henky Udiansyah	\N	$2y$12$fZAqTT7Zsu.FC/fqwK2WJ.ta0grxr4GPGgxZ77a225zUpUpmrTYOi	\N	\N	\N	2026-09-10 10:40:29	2026-09-22 14:48:53	\N	t	f	\N	Green	\N	1	\N	evaluasi_3	trainee	0.0	0.0
\.


--
-- TOC entry 5261 (class 0 OID 0)
-- Dependencies: 235
-- Name: competency_evaluations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.competency_evaluations_id_seq', 1, true);


--
-- TOC entry 5262 (class 0 OID 0)
-- Dependencies: 221
-- Name: equipment_categories_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.equipment_categories_id_seq', 9, true);


--
-- TOC entry 5263 (class 0 OID 0)
-- Dependencies: 223
-- Name: equipments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.equipments_id_seq', 4, true);


--
-- TOC entry 5264 (class 0 OID 0)
-- Dependencies: 248
-- Name: legacy_sqlite_archives_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.legacy_sqlite_archives_id_seq', 1, false);


--
-- TOC entry 5265 (class 0 OID 0)
-- Dependencies: 241
-- Name: logbook_assignments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.logbook_assignments_id_seq', 1, false);


--
-- TOC entry 5266 (class 0 OID 0)
-- Dependencies: 231
-- Name: logbook_evidences_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.logbook_evidences_id_seq', 1, true);


--
-- TOC entry 5267 (class 0 OID 0)
-- Dependencies: 233
-- Name: logbook_histories_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.logbook_histories_id_seq', 8, true);


--
-- TOC entry 5268 (class 0 OID 0)
-- Dependencies: 219
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 49, true);


--
-- TOC entry 5269 (class 0 OID 0)
-- Dependencies: 243
-- Name: ojt_final_evaluations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ojt_final_evaluations_id_seq', 1, false);


--
-- TOC entry 5270 (class 0 OID 0)
-- Dependencies: 229
-- Name: ojt_logbooks_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ojt_logbooks_id_seq', 37, true);


--
-- TOC entry 5271 (class 0 OID 0)
-- Dependencies: 245
-- Name: trainee_phase_histories_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.trainee_phase_histories_id_seq', 1, false);


--
-- TOC entry 5272 (class 0 OID 0)
-- Dependencies: 239
-- Name: trainee_trainer_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.trainee_trainer_id_seq', 1, false);


--
-- TOC entry 5273 (class 0 OID 0)
-- Dependencies: 225
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 16, true);


--
-- TOC entry 5019 (class 2606 OID 17191)
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- TOC entry 5016 (class 2606 OID 17180)
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- TOC entry 5011 (class 2606 OID 17160)
-- Name: competency_evaluations competency_evaluations_ojt_logbook_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.competency_evaluations
    ADD CONSTRAINT competency_evaluations_ojt_logbook_id_unique UNIQUE (ojt_logbook_id);


--
-- TOC entry 5013 (class 2606 OID 17148)
-- Name: competency_evaluations competency_evaluations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.competency_evaluations
    ADD CONSTRAINT competency_evaluations_pkey PRIMARY KEY (id);


--
-- TOC entry 4983 (class 2606 OID 16963)
-- Name: equipment_categories equipment_categories_code_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.equipment_categories
    ADD CONSTRAINT equipment_categories_code_unique UNIQUE (code);


--
-- TOC entry 4985 (class 2606 OID 16961)
-- Name: equipment_categories equipment_categories_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.equipment_categories
    ADD CONSTRAINT equipment_categories_pkey PRIMARY KEY (id);


--
-- TOC entry 4987 (class 2606 OID 16978)
-- Name: equipments equipments_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.equipments
    ADD CONSTRAINT equipments_pkey PRIMARY KEY (id);


--
-- TOC entry 4989 (class 2606 OID 16985)
-- Name: equipments equipments_unit_code_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.equipments
    ADD CONSTRAINT equipments_unit_code_unique UNIQUE (unit_code);


--
-- TOC entry 5036 (class 2606 OID 17417)
-- Name: legacy_sqlite_archives legacy_sqlite_archives_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.legacy_sqlite_archives
    ADD CONSTRAINT legacy_sqlite_archives_pkey PRIMARY KEY (id);


--
-- TOC entry 5038 (class 2606 OID 17419)
-- Name: legacy_sqlite_archives legacy_sqlite_archives_source_table_source_id_source_column_uni; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.legacy_sqlite_archives
    ADD CONSTRAINT legacy_sqlite_archives_source_table_source_id_source_column_uni UNIQUE (source_table, source_id, source_column);


--
-- TOC entry 5025 (class 2606 OID 17268)
-- Name: logbook_assignments logbook_assignments_ojt_logbook_id_user_id_role_type_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.logbook_assignments
    ADD CONSTRAINT logbook_assignments_ojt_logbook_id_user_id_role_type_unique UNIQUE (ojt_logbook_id, user_id, role_type);


--
-- TOC entry 5027 (class 2606 OID 17256)
-- Name: logbook_assignments logbook_assignments_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.logbook_assignments
    ADD CONSTRAINT logbook_assignments_pkey PRIMARY KEY (id);


--
-- TOC entry 5007 (class 2606 OID 17107)
-- Name: logbook_evidences logbook_evidences_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.logbook_evidences
    ADD CONSTRAINT logbook_evidences_pkey PRIMARY KEY (id);


--
-- TOC entry 5009 (class 2606 OID 17125)
-- Name: logbook_histories logbook_histories_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.logbook_histories
    ADD CONSTRAINT logbook_histories_pkey PRIMARY KEY (id);


--
-- TOC entry 4981 (class 2606 OID 16935)
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- TOC entry 5034 (class 2606 OID 17374)
-- Name: notifications notifications_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notifications
    ADD CONSTRAINT notifications_pkey PRIMARY KEY (id);


--
-- TOC entry 5029 (class 2606 OID 17307)
-- Name: ojt_final_evaluations ojt_final_evaluations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ojt_final_evaluations
    ADD CONSTRAINT ojt_final_evaluations_pkey PRIMARY KEY (id);


--
-- TOC entry 5003 (class 2606 OID 17091)
-- Name: ojt_logbooks ojt_logbooks_logbook_number_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ojt_logbooks
    ADD CONSTRAINT ojt_logbooks_logbook_number_unique UNIQUE (logbook_number);


--
-- TOC entry 5005 (class 2606 OID 17059)
-- Name: ojt_logbooks ojt_logbooks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ojt_logbooks
    ADD CONSTRAINT ojt_logbooks_pkey PRIMARY KEY (id);


--
-- TOC entry 4997 (class 2606 OID 17020)
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- TOC entry 5000 (class 2606 OID 17030)
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- TOC entry 5031 (class 2606 OID 17346)
-- Name: trainee_phase_histories trainee_phase_histories_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.trainee_phase_histories
    ADD CONSTRAINT trainee_phase_histories_pkey PRIMARY KEY (id);


--
-- TOC entry 5021 (class 2606 OID 17227)
-- Name: trainee_trainer trainee_trainer_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.trainee_trainer
    ADD CONSTRAINT trainee_trainer_pkey PRIMARY KEY (id);


--
-- TOC entry 5023 (class 2606 OID 17239)
-- Name: trainee_trainer trainee_trainer_trainee_id_trainer_id_trainer_type_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.trainee_trainer
    ADD CONSTRAINT trainee_trainer_trainee_id_trainer_id_trainer_type_unique UNIQUE (trainee_id, trainer_id, trainer_type);


--
-- TOC entry 4991 (class 2606 OID 17401)
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- TOC entry 4993 (class 2606 OID 17002)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 4995 (class 2606 OID 17009)
-- Name: users users_sid_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_sid_unique UNIQUE (sid);


--
-- TOC entry 5014 (class 1259 OID 17181)
-- Name: cache_expiration_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX cache_expiration_index ON public.cache USING btree (expiration);


--
-- TOC entry 5017 (class 1259 OID 17192)
-- Name: cache_locks_expiration_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX cache_locks_expiration_index ON public.cache_locks USING btree (expiration);


--
-- TOC entry 5032 (class 1259 OID 17372)
-- Name: notifications_notifiable_type_notifiable_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX notifications_notifiable_type_notifiable_id_index ON public.notifications USING btree (notifiable_type, notifiable_id);


--
-- TOC entry 4998 (class 1259 OID 17032)
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- TOC entry 5001 (class 1259 OID 17031)
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- TOC entry 5052 (class 2606 OID 17149)
-- Name: competency_evaluations competency_evaluations_ojt_logbook_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.competency_evaluations
    ADD CONSTRAINT competency_evaluations_ojt_logbook_id_foreign FOREIGN KEY (ojt_logbook_id) REFERENCES public.ojt_logbooks(id) ON DELETE CASCADE;


--
-- TOC entry 5053 (class 2606 OID 17154)
-- Name: competency_evaluations competency_evaluations_trainer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.competency_evaluations
    ADD CONSTRAINT competency_evaluations_trainer_id_foreign FOREIGN KEY (trainer_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- TOC entry 5039 (class 2606 OID 16979)
-- Name: equipments equipments_equipment_category_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.equipments
    ADD CONSTRAINT equipments_equipment_category_id_foreign FOREIGN KEY (equipment_category_id) REFERENCES public.equipment_categories(id) ON DELETE CASCADE;


--
-- TOC entry 5056 (class 2606 OID 17257)
-- Name: logbook_assignments logbook_assignments_ojt_logbook_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.logbook_assignments
    ADD CONSTRAINT logbook_assignments_ojt_logbook_id_foreign FOREIGN KEY (ojt_logbook_id) REFERENCES public.ojt_logbooks(id) ON DELETE CASCADE;


--
-- TOC entry 5057 (class 2606 OID 17262)
-- Name: logbook_assignments logbook_assignments_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.logbook_assignments
    ADD CONSTRAINT logbook_assignments_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- TOC entry 5049 (class 2606 OID 17108)
-- Name: logbook_evidences logbook_evidences_ojt_logbook_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.logbook_evidences
    ADD CONSTRAINT logbook_evidences_ojt_logbook_id_foreign FOREIGN KEY (ojt_logbook_id) REFERENCES public.ojt_logbooks(id) ON DELETE CASCADE;


--
-- TOC entry 5050 (class 2606 OID 17126)
-- Name: logbook_histories logbook_histories_ojt_logbook_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.logbook_histories
    ADD CONSTRAINT logbook_histories_ojt_logbook_id_foreign FOREIGN KEY (ojt_logbook_id) REFERENCES public.ojt_logbooks(id) ON DELETE CASCADE;


--
-- TOC entry 5051 (class 2606 OID 17131)
-- Name: logbook_histories logbook_histories_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.logbook_histories
    ADD CONSTRAINT logbook_histories_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- TOC entry 5058 (class 2606 OID 17331)
-- Name: ojt_final_evaluations ojt_final_evaluations_hse_approved_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ojt_final_evaluations
    ADD CONSTRAINT ojt_final_evaluations_hse_approved_by_foreign FOREIGN KEY (hse_approved_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- TOC entry 5059 (class 2606 OID 17326)
-- Name: ojt_final_evaluations ojt_final_evaluations_pjo_approved_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ojt_final_evaluations
    ADD CONSTRAINT ojt_final_evaluations_pjo_approved_by_foreign FOREIGN KEY (pjo_approved_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- TOC entry 5060 (class 2606 OID 17321)
-- Name: ojt_final_evaluations ojt_final_evaluations_tc_approved_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ojt_final_evaluations
    ADD CONSTRAINT ojt_final_evaluations_tc_approved_by_foreign FOREIGN KEY (tc_approved_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- TOC entry 5061 (class 2606 OID 17313)
-- Name: ojt_final_evaluations ojt_final_evaluations_trainer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ojt_final_evaluations
    ADD CONSTRAINT ojt_final_evaluations_trainer_id_foreign FOREIGN KEY (trainer_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- TOC entry 5041 (class 2606 OID 17202)
-- Name: ojt_logbooks ojt_logbooks_assigned_pjo_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ojt_logbooks
    ADD CONSTRAINT ojt_logbooks_assigned_pjo_id_foreign FOREIGN KEY (assigned_pjo_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- TOC entry 5042 (class 2606 OID 17207)
-- Name: ojt_logbooks ojt_logbooks_assigned_tc_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ojt_logbooks
    ADD CONSTRAINT ojt_logbooks_assigned_tc_id_foreign FOREIGN KEY (assigned_tc_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- TOC entry 5043 (class 2606 OID 17080)
-- Name: ojt_logbooks ojt_logbooks_equipment_category_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ojt_logbooks
    ADD CONSTRAINT ojt_logbooks_equipment_category_id_foreign FOREIGN KEY (equipment_category_id) REFERENCES public.equipment_categories(id) ON DELETE SET NULL;


--
-- TOC entry 5044 (class 2606 OID 17085)
-- Name: ojt_logbooks ojt_logbooks_equipment_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ojt_logbooks
    ADD CONSTRAINT ojt_logbooks_equipment_id_foreign FOREIGN KEY (equipment_id) REFERENCES public.equipments(id) ON DELETE SET NULL;


--
-- TOC entry 5045 (class 2606 OID 17161)
-- Name: ojt_logbooks ojt_logbooks_pjo_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ojt_logbooks
    ADD CONSTRAINT ojt_logbooks_pjo_id_foreign FOREIGN KEY (pjo_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- TOC entry 5046 (class 2606 OID 17060)
-- Name: ojt_logbooks ojt_logbooks_trainee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ojt_logbooks
    ADD CONSTRAINT ojt_logbooks_trainee_id_foreign FOREIGN KEY (trainee_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- TOC entry 5047 (class 2606 OID 17065)
-- Name: ojt_logbooks ojt_logbooks_trainer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ojt_logbooks
    ADD CONSTRAINT ojt_logbooks_trainer_id_foreign FOREIGN KEY (trainer_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- TOC entry 5048 (class 2606 OID 17166)
-- Name: ojt_logbooks ojt_logbooks_training_centre_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ojt_logbooks
    ADD CONSTRAINT ojt_logbooks_training_centre_id_foreign FOREIGN KEY (training_centre_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- TOC entry 5062 (class 2606 OID 17357)
-- Name: trainee_phase_histories trainee_phase_histories_approved_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.trainee_phase_histories
    ADD CONSTRAINT trainee_phase_histories_approved_by_foreign FOREIGN KEY (approved_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- TOC entry 5063 (class 2606 OID 17352)
-- Name: trainee_phase_histories trainee_phase_histories_evaluation_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.trainee_phase_histories
    ADD CONSTRAINT trainee_phase_histories_evaluation_id_foreign FOREIGN KEY (evaluation_id) REFERENCES public.ojt_final_evaluations(id) ON DELETE SET NULL;


--
-- TOC entry 5064 (class 2606 OID 17347)
-- Name: trainee_phase_histories trainee_phase_histories_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.trainee_phase_histories
    ADD CONSTRAINT trainee_phase_histories_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- TOC entry 5054 (class 2606 OID 17228)
-- Name: trainee_trainer trainee_trainer_trainee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.trainee_trainer
    ADD CONSTRAINT trainee_trainer_trainee_id_foreign FOREIGN KEY (trainee_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- TOC entry 5055 (class 2606 OID 17233)
-- Name: trainee_trainer trainee_trainer_trainer_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.trainee_trainer
    ADD CONSTRAINT trainee_trainer_trainer_id_foreign FOREIGN KEY (trainer_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- TOC entry 5040 (class 2606 OID 17270)
-- Name: users users_equipment_category_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_equipment_category_id_foreign FOREIGN KEY (equipment_category_id) REFERENCES public.equipment_categories(id) ON DELETE SET NULL;


-- Completed on 2026-09-22 15:35:12

--
-- PostgreSQL database dump complete
--

\unrestrict hSq3k7y95UtpYcbeSajraiqN9GkULul4DQwVQ73vcBYarvCtaezyT1sMGgTpz7C

